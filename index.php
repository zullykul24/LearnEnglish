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
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learn English</title>
    <link rel="stylesheet" href="style.css?v=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
    
    </head>
    <body>
        <div class="container-fluid pt-5 pt-lg-0 bg-light  w-100">
        <div id="header" class="row text-light p-2 vh-10  bg-dark fixed-top align-items-center mb-5 ">
            <div id="logo" class="col-12 col-lg-3 pl-lg-5">
                <a href='index.php'  >
                  <span >Learn English </span>
                </a>
            </div>
            <div class="col-lg-1"></div>
    
            <div class="col-12 col-lg-3  ">
                  
                    <span id="name" class="header-item">
                    <?php
                    if(isset($_COOKIE['username'])){
                      if($_COOKIE['username'] == 'admin')header("location:admin.php", true, 301);
                      else echo "Xin chào ".$_COOKIE['username'];
                    } else {
                    header("location:signin.php", true, 301);
                    }
                    ?>
                    </span>
                </div>
                <div class="col-12 col-lg-2 ">
                    <span id="point" class="header-item">
                    <?php
                        $user = $_COOKIE['username'];
                        $sql = "SELECT diem from tai_khoan WHERE username= '{$user}'";
                        $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                        echo "Điểm: ".$diem['diem'];
                    ?>
                    </span>
                   
                  
            </div>
            <div class="col-12 col-lg-3 pr-lg-5 text-lg-right "> <a id="signout-text" class="header-item  " href="signout.php">Đăng xuất</a></div>
        </div>
        <div id="search-bar" class="row justify-content-center mt-5 pt-5 pt-lg-0 py-2 bg-light  " >
            <div class="col-12 col-lg-7">
            <form action="index.php" method="get" id="form-search" class="pt-3">
                <div class="row align-items-center py-0 py-md-2 py-lg-0">
                <div class="col-6 col-lg-3 mt-1 ">
                    <input type="text"  id="search" name="search" class="w-100" placeholder="Nhập từ cần tìm" value = <?php if(isset( $_GET['search']) && $_GET['search'] != "")
                    echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES);?> ></div>
                <div class="col-6 col-lg-3">
                    <select id="select" name="select" class="w-100" >
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
                        //////
                        }
                    ?>
                    </select>
                </div>
                <div class="col-6 col-lg-3 mt-2 mt-lg-0 ">
                    <select id="dif" name="dif" class="w-100"   >
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
                        //////
                        }
                    ?>
                    </select>
                </div>
                <div class="col-6 col-lg-2 mt-2 mt-lg-0">
                    <select id="status" name="status" class="w-100"  >
                    <option  style="display:none" disabled selected value >
                    <?php if(isset($_GET['status']) && $_GET['status'] != "") echo $_GET['status'];
                      else echo "Tất cả từ"?>
                    </option>
                    <option>Chưa học</option>
                    <option>Đã học</option>
    
                    </select>
                </div>
                <div class="col-12 col-lg-1 mt-3 mt-lg-0 text-center">
                <input type="submit" class="btn btn-primary " value="Tìm kiếm"></div>
              </div>
              </form>
              
              </div>
              
              
        </div>
        <div id="container" class=" row  mt-0 ">

            
            <div id="main-container" class="col-12 col-lg-9  d-flex flex-column flex-xl-row flex-wrap justify-content-between px-0  px-lg-0 pt-2 pb-2 align-content-around main-container position-relative bg-white">         
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
                return "bg-success' >Đã học";
            }
            else {
                return "bg-danger'>Học";
            }
            }



            while($bang_tu_vung = mysqli_fetch_array($danhSachTu)){
            $word_id = $bang_tu_vung['ID_tu'];

            // Danh sách từ chưa học
            echo 
            "<div class='word-block row  align-content-center mx-auto my-5 m-lg-5  p-1 p-lg-3  ' style='width: 80%;'  >
                <div class='word-image col-6'><img src='img/".$bang_tu_vung['url_hinhanh']."' width='100%'></div>
                <div class='p-0 col-6 '>
                <div class='word-text row pl-2'>".$bang_tu_vung['Tu_vung']."</div>
                <div class='word-pronunciation pl-2 row'>".$bang_tu_vung['Phat_am']."</div>
                <div class='word-meaning row pl-2'>".$bang_tu_vung['Nghia']."</div>
                <div class='row justify-content-around align-items-center mt-4 pt-4 pt-lg-3 mt-lg-5  ' >
                <div class='word-reading py-1 py-lg-2 rounded bg-info text-center col-4'>Đọc</div>
                <div  style='font-size: 100%;' id ='".$word_id."' class='word-status py-1 py-lg-2 rounded text-center col-6 mr-3 mr-lg-0 ".status($word_id)."</div>
                </div>
                </div> 
            </div>";
            }   
            ?>
            <!-- <div class='word-block row  align-content-center mx-auto my-5 m-lg-5  p-1 p-lg-3  ' style="width: 80%;"  >
                        <div class='word-image col-6'><img src="img/Bear.jpg" width="100%" ></div>
                        <div class="p-0 col-6 ">
                                <div class='word-text row pl-2'>Tên từ vựng</div>
                                <div class='word-pronunciation pl-2 row'>Phát âm</div>
                                <div class='word-meaning row pl-2'>Nghĩa</div>
                                
                                <div class="row justify-content-around align-items-center mt-4 pt-4 pt-lg-3 mt-lg-5  " >
                                    <div class='word-reading  py-1 py-lg-2 rounded bg-info text-center col-4  '  >Đọc</div>
                                    <div class='word-status py-1 py-lg-2 rounded text-center bg-danger col-6 mr-3 mr-lg-0 ' style="font-size: 100%;"  >Đã học</div>
                                    </div>
                                </div>
                        </div>
            
            
    
                 </div>-->

            
            
            
            </div>
            
            <div class="col-12 col-lg-3 ">
                <div class="col-12 py-2 text-center">Bảng xếp hạng top 10</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Ten</th>
                            <th scope="col">Diem</th>
                        </tr>
                    </thead>
                    <tbody id="top-score-container">
                    
                    <?php 
                    $truyvan= "select username , diem from tai_khoan order by diem desc limit 10";
                    $diemcao = mysqli_query($conn, $truyvan);
                   $array = array();
                   while($danhsachdiemcao = mysqli_fetch_array($diemcao)){
                       array_push($GLOBALS['array'], $danhsachdiemcao);
                   }
                   for($i=0;$i<count($array);$i++){
                    echo
                    "<tr class='text-success'>
                        <th scope='row'>".($i+1)."</th>
                        <td>".$array[$i][0]."</td>
                        <td>".$array[$i][1]."</td>
                    </tr>";
                      
                   }
                   $count_top = false;
                   for($i=0;$i<count($array);$i++){
                       if($array[$i][0] == $user){
                           $count_top = true;
                       }
                       else continue;
                   }
                   if($count_top == false){
                   $sql_rank = "SELECT r.rank from(SELECT username,RANK() OVER(order by diem desc)rank from tai_khoan) as r WHERE r.username  = '{$user}'";
                   //$diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                     //   echo "Điểm: ".$diem['diem'];
                     $rank_fetch = mysqli_fetch_array(mysqli_query($conn, $sql_rank), MYSQLI_ASSOC);
                    echo "<tr>
                    <th scope='row'>...</th>
                    <td>....................</td>
                    <td>....</td>
                    </tr>
                    <tr>
                    <th scope='row'>".$rank_fetch['rank']."</th>
                    <td>".$user."</td>
                    <td>".$diem['diem']."</td>
                    </tr>";
                   }
                    ?>
                    </tbody>
                  </table>
            </div>
        </div>
        <div id="footer" class="row  p-3 justify-content-between align-items-center">
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
       ?>"><div id="prev-page" class="col">Trang trước</div>
       </a>

        <div id="page-number" class="col text-center"> Trang <?php echo $trang ?> trên tổng số <?php echo $so_trang ?> trang</div>
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
             }?>"><div id="next-page" class="col">Trang sau</div>
        </a>
        </div>
    </div>
<!--  js -->
<script>

function updatePoint(){
      $(document).ready(function(){
          var url = "updatepoint.php?t=" + Math.random();
          var data = {"action" : "update_point"};
         
          $("#point").load(url,data);
          
      });
      
}
function updateTop(){
  $(document).ready(function(){
          var url = "updatetop.php?t=" + Math.random();
          
          var updateTopScore = {"top": "update_top"};
          
          $("#top-score-container").load(url, updateTopScore);
      });
}
//updatePoint();
//updateTop();
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
        await updateTop();
            
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
        await updateTop();
            
}

$(document).ready(function(){
      $(".word-status").click(function(){
        if($(this).text() == "Học"){
          console.log($(this).attr('id'));
          addPoint($(this).attr('id'));
          this.classList.remove("bg-danger");
          this.classList.add("bg-success");
          this.innerHTML = "Đã học";
   
          }
          else {
            console.log($(this).attr('id'));
          subPoint($(this).attr('id'));
          this.classList.remove("bg-success");
          this.classList.add("bg-danger");
          this.innerHTML = "Học";
            
          
          }
        });
      
});
/// words speaker



</script>

<script src="soundRead.js?v=2"></script>
    </body>
    
</html>