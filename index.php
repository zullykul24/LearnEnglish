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

                    <input type="text" id="search" name="search" placeholder="Nhập từ cần tìm" value = <?php if(isset( $_GET['search']) && $_GET['search'] != "") echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES);?>>
                    
                    <select id="select" name="select" >
                      <option  style="display:none" disabled selected value>
                      <?php if(isset($_GET['select']) && $_GET['select'] != "") echo $_GET['select'];
                      else echo "Chọn chủ đề"?>
                      </option>
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
                    <select id="dif" name="dif"  >
                      <option  style="display:none" disabled selected value >
                      <?php if(isset($_GET['dif']) && $_GET['dif'] != "") echo $_GET['dif'];
                      else echo "Chọn độ khó"?>
                     </option>
                      <?php

                        $sql = "SELECT * from do_kho";
                        $do_kho =  $conn->query($sql);
                        if ($do_kho->num_rows > 0) {
                        // output data of each row
                        while($row = $do_kho->fetch_assoc()) {
                            echo "<option>".$row["Ten_do_kho"]."</option>" ;
                        }
                        } else {
                        
                        }

                        ?>
      
                    </select>
                    <select id="status" name="status"  >
                      <option  style="display:none" disabled selected value >
                      <?php if(isset($_GET['status']) && $_GET['status'] != "") echo $_GET['status'];
                      else echo "Tất cả từ"?>
                     </option>
                      <option>Chưa học</option>
                      <option>Đã học</option>
      
                    </select>
      
                    <input type="submit" value="Tìm kiếm">
                  </form>
                  
            </div>
            <div id="main-container">
            <!-----------------------------------------------------danh sach diem cao----------->
              <div id="score">
                <table>
                  <tr>
                    <td>
                      Danh sách điểm cao:
                    <td>
                  </tr>
                  
                  <?php 
                    $truyvan= "select username , diem from tai_khoan order by diem desc limit 10";
                    $diemcao = mysqli_query($conn, $truyvan);
                    while($danhsachdiemcao = mysqli_fetch_array($diemcao)){
                      $diemtungnguoi = $danhsachdiemcao['diem'];
                      $nguoidung = $danhsachdiemcao['username'];
        
                      // Danh sách từ chưa học
                      echo "<tr><td>".$nguoidung."</td><td>".$diemtungnguoi."</td></tr>";
                    }



                  ?>
                </table>
              </div>

              <!------------------------------------------------------------------------------------->
            <?php  


            //get user_id
            $sqlSelectUserId = "SELECT user_id from tai_khoan where username = '{$user}' ";
            $userID = mysqli_fetch_array(mysqli_query($conn, $sqlSelectUserId), MYSQLI_ASSOC);

            $from = ($trang - 1)* $so_tu_mot_trang;


            $ve1 = " select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' ) ";

            $ve2 = " select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' ) ";

            if(isset($_GET["select"])){
              $sqlselect = "SELECT * FROM chu_de WHERE Ten_chu_de='{$_GET["select"]}'";
              $selectsql = mysqli_fetch_array(mysqli_query($conn , $sqlselect), MYSQLI_ASSOC);/// sua lai ten cot 'Ten chu de' -> Ten_chu_de
              
              

              $select = " and ID_chude = '{$selectsql['ID_chude']}' ";///// chua xong
            }
            else{
              $select="";
            }
            
            if(isset($_GET["dif"]) && $_GET['dif'] != ""){
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
            if(isset($_GET['status'])){
              if($_GET['status'] == "Chưa học")  $sqlSapXepTu= $ve1.$select.$search.$dif;
              else if($_GET['status'] == "Đã học")  $sqlSapXepTu= $ve2.$select.$search.$dif;
              else $sqlSapXepTu= $ve1.$select.$search.$dif." UNION ".$ve2.$select.$search.$dif;
            } else {

            //Override
            $sqlSapXepTu= $ve1.$select.$search.$dif." UNION ".$ve2.$select.$search.$dif;
            }
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
            <a href = "index.php?trang=<?php 
            if($trang >1)echo $trang - 1;
            else echo "1"; 

            
           
            if (isset($_GET['select']) && $_GET['select'] != ""){
              echo "&select={$_GET['select']}";
          }
           if (isset($_GET['search']) && $_GET['search'] != ""){
              echo "&search={$_GET['search']}";
          }
          if (isset($_GET['dif']) && $_GET['dif'] != ""){
            echo "&dif={$_GET['dif']}";
        }
        if (isset($_GET['status']) && $_GET['status'] != ""){
          echo "&status={$_GET['status']}";
      }



            ?>
            "><div id="prev-page">Trang trước</div></a>

            <div id="page-number"> Trang <?php echo $trang ?> trên tổng số <?php echo $so_trang ?> trang</div>
            <a href = "index.php?trang=<?php
             if($trang < $so_trang)echo $trang + 1;
             else echo $trang; 
             
           
             if (isset($_GET['select']) && $_GET['select'] != ""){
              echo "&select={$_GET['select']}";
          }
           if (isset($_GET['search']) && $_GET['search'] != ""){
              echo "&search={$_GET['search']}";
          }
          if (isset($_GET['dif']) && $_GET['dif'] != ""){
            echo "&dif={$_GET['dif']}";
        }
        if (isset($_GET['status']) && $_GET['status'] != ""){
          echo "&status={$_GET['status']}";
      }
              
              

            ?>
             "><div id="next-page">Trang sau</div></a>
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
   
          }
          else {
            console.log($(this).attr('id'));
          subPoint($(this).attr('id'));
          this.style = "background-color: none";
          this.innerHTML = "Học";
            
          
          }
        });
      
})
</script>
<script src="soundReader.js"></script>
    </body>
</html>