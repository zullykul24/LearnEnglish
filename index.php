<?php
require 'dbconn.php';
$so_tu_mot_trang = 6;
if(isset($_GET["trang"])){
$trang = $_GET["trang"];
settype($trang, "int");
}
else{
    $trang = 1;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    <title>Learn English</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href = "newstyle.css">
    </head>
    <body>
        <div id="header">
            <div id="logo">
                <a href='index.php' style="text-decoration:none; color: black; max-width:100px ;margin-left:30px;" >
                  <span style='font-size:25px'>Learn English </span>
                </a>
            </div>
    
            <div id="header-info">
                  
                    <span id="name" class="header-item">
                    <?php
                    if(isset($_COOKIE['username'])){
                    echo "Xin chào ".$_COOKIE['username'];
                    } else {
                    header("location:signin.php");
                    }
                    ?>
                    </span>
                    <span id="point" class="header-item">
                    
                     <?php
                        $user = $_COOKIE['username'];
                        $sql = "SELECT diem from tai_khoan WHERE username= '{$user}'";
                        $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                        echo "Điểm: ".$diem['diem'];
                        ?>
                    </span>
                    <a id="signout-text" class="header-item" style="cursor:pointer; text-decoration: none; color: black;" href="signout.php">Đăng xuất</a>
                  
            </div>
        </div>
        <div id="container">
            <div id="search-bar">
                <form action="index.php" method="get" id="form-search">

                    <input type="text" id="search" name="search" placeholder="Nhập từ cần tìm" value = <?php if(isset( $_GET['search'])) echo $_GET['search']?>>
                    
                    <select id="select" name="select" value = <?php if(isset( $_GET['select'])) echo $_GET['select']?> >
                      <option  style="display:none" disabled selected value>Chọn chủ đề</option>
                      <?php

                        $sql = "SELECT * from chu_de";
                        $chu_de =  $conn->query($sql);
                        if ($chu_de->num_rows > 0) {
                        // output data of each row
                        while($row = $chu_de->fetch_assoc()) {
                            echo "<option>".$row["Ten_chu_de"]."</option>" ;
                        }
                        } else {
                        
                        }

                        ?>
      
                    </select>
                    <select id="dif" name="dif" value = <?php echo $_GET['dif']?> >
                      <option  style="display:none" disabled selected value>Chọn độ khó</option>
                      <?php

                        $sql = "SELECT * from do_kho";
                        $chu_de =  $conn->query($sql);
                        if ($chu_de->num_rows > 0) {
                        // output data of each row
                        while($row = $chu_de->fetch_assoc()) {
                            echo "<option>".$row["Ten_do_kho"]."</option>" ;
                        }
                        } else {
                        
                        }

                        ?>
      
                    </select>
      
                    <input type="submit" value="Tìm kiếm">
                  </form>
            </div>
            <div id="main-container">
               
            <?php  


            //get user_id
            $sqlSelectUserId = "SELECT user_id from tai_khoan where username = '{$user}' ";
            $userID = mysqli_fetch_array(mysqli_query($conn, $sqlSelectUserId), MYSQLI_ASSOC);

            $from = ($trang - 1)* $so_tu_mot_trang;

            // Chưa học xếp trước, union với đã học xếp sau
         /*   $sqlSapXepTu = "select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')
            UNION 
            select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')

            ";*/

            $ve1 = " select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            $ve2 = " select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            if(isset($_GET["select"])){
              $sqlselect = "SELECT * FROM chu_de WHERE Ten_chu_de='{$_GET["select"]}'";
              $selectsql = mysqli_fetch_array(mysqli_query($conn , $sqlselect), MYSQLI_ASSOC);/// sua lai ten cot 'Ten chu de' -> Ten_chu_de
              
              //echo "selectsql = ".$selectsql['ID_chude']";

              $select = " and ID_chude = '{$selectsql['ID_chude']}' ";///// chua xong
            }
            else{
              $select="";
            }
            
            if(isset($_GET["dif"])){
              $sqldif = "SELECT * FROM do_kho WHERE Ten_do_kho='{$_GET["dif"]}'";
              $difsql = mysqli_fetch_array(mysqli_query($conn , $sqldif), MYSQLI_ASSOC);/// sua lai ten cot 'Ten chu de' -> Ten_chu_de
              
              

              $dif = " and ID_dokho = '{$difsql['ID_dokho']}' ";
            }
            else{
              $dif="";
            }




            if(empty($_GET["search"])){
              $search="";
            }
            else{
              $search =  " and ((SELECT LOCATE('{$_GET['search']}', Tu_vung)) > 0 or (SELECT LOCATE('{$_GET['search']}' , Nghia)) > 0) ";
            }

            //Override
            $sqlSapXepTu= $ve1.$select.$search.$dif." UNION ".$ve2.$select.$search.$dif;
            //  echo $sqlSapXepTu;

            /////////////////////////////////////////////////////////////////////////

            $sqlDanhSachTu = $sqlSapXepTu." limit $from, $so_tu_mot_trang";
            $danhSachTu = mysqli_query($conn, $sqlDanhSachTu);

            function status($word){
              if (mysqli_num_rows(mysqli_query($GLOBALS['conn'],"SELECT * FROM user_word 
              WHERE user_id = '{$GLOBALS['userID']['user_id']}' AND ID_tu = '{$word}' ")) > 0){
                return " style = 'background-color:pink' >Đã học";
              }
              else {
                return ">Học";
              }
            }



            while($bang_tu_vung = mysqli_fetch_array($danhSachTu)){
              $word_id = $bang_tu_vung['ID_tu'];

              // Danh sách từ chưa học
              echo "<div class='word-block'>
              <div class='word-image'><img src='img/".$bang_tu_vung['url_hinhanh']."'></div>
              <div class='word-text'>".$bang_tu_vung['Tu_vung']."</div>
              <div class='word-pronunciation'>".$bang_tu_vung['Phat_am']."</div>
              <div class='word-meaning'>".$bang_tu_vung['Nghia']."</div>
              <div class='word-reading'>Đọc</div>
              <div class='word-status' id ='".$word_id."'".status($word_id)."</div></div>";
            }   
            ?>
            </div>
            
        </div>
        <div id="footer">
        <?php 
  
        $query_tong = $sqlSapXepTu;

        $execute_tong = mysqli_query($conn, $query_tong);
        $tong_so_tu = mysqli_num_rows($execute_tong);
        $so_trang = ceil($tong_so_tu/$so_tu_mot_trang);
        
        
        ?> 
            <div id="prev-page"><a href = "index.php?trang=<?php 
            if($trang >1)echo $trang - 1;
            else echo "1"; 

            
            /*
            if(isset($_GET['select']) && isset($_GET['search'])){
                echo "&search={$_GET['search']}&select={$_GET['select']}";
            }
            else if (isset($_GET['select'])){
                echo "&select={$_GET['select']}";
            }
            else if (isset($_GET['search'])){
                echo "&search={$_GET['search']}";
            }*/
            $echo_out="";
            if (isset($_GET['select'])){
              $echo_out .= "&select={$_GET['select']}";
            }
            if (isset($_GET['dif'])){
              $echo_out .= "&dif={$_GET['dif']}";
            }
            if (isset($_GET['search'])){
              $echo_out .= "&search={$_GET['search']}";
            }

            echo $echo_out;



            ?>
            ">Trang trước</a></div>

            <div id="page-number"> Trang <?php echo $trang ?> tren tong so <?php echo $so_trang ?> trang</div>
            <div id="next-page"><a href = "index.php?trang=<?php
             if($trang < $so_trang)echo $trang + 1;
             else echo $trang; 
             /*
             if(isset($_GET['select']) && isset($_GET['search'])){
                echo "&search={$_GET['search']}&select={$_GET['select']}";
            }
                else if (isset($_GET['select'])){
                    echo "&select={$_GET['select']}";
                }
                else if (isset($_GET['search'])){
                    echo "&search={$_GET['search']}";
                }
              */
              echo $echo_out;

            ?>
             ">Trang sau</a></div>
        </div>
        <script>

function updatePoint(){
      $(document).ready(function(){
          var url = "updatepoint.php?t=" + Math.random();
          var data = {"action" : "update_point"};
          $("#point").load(url,data);
      });
      
}
updatePoint();
async function addPoint(id){
  //ctrl + k + c: comment
  //ctrl + k + u: uncomment
        
        await $.ajax({
          type: "POST",
          url: "addpoint.php?t=" + Math.random(),
          dataType: "html",
          data: {
            action: "add_point",
            word_id: id
          }
          

        })
        await updatePoint(); 
            
}
async function subPoint(id){
  //ctrl + k + c: comment
  //ctrl + k + u: uncomment
        
        await $.ajax({
          type: "POST",
          url: "subpoint.php?t=" + Math.random(),
          dataType: "html",
          data: {
            action: "sub_point",
            word_id: id
          }
          

        })
        await updatePoint(); 
            
}

$(document).ready(function(){
      $(".word-status").click(function(){
        if($(this).text() == "Học"){
          console.log($(this).attr('id'));
          addPoint($(this).attr('id'));
          this.style = "background-color: pink";
          this.innerHTML = "Đã học";
         // $(this).unbind("click");
          }
          else {
            console.log($(this).attr('id'));
          subPoint($(this).attr('id'));
          this.style = "background-color: none";
          this.innerHTML = "Học";
            
            //$(this).unbind("click");
          }
        });
      
})
</script>
<script src="soundReader.js"></script>
    </body>
</html>