<?php
require 'dbconn.php';
$so_tu_mot_trang = 8;
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
    <title>Test page</title>
    <link href="huytest/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>


    <div id="header">
      <div class="logo">
      <a href='index.php' style="text-decoration:none; color: black; max-width:100px ;margin-left:30px;background: -webkit-linear-gradient(cyan, blue);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;" >
        <span style='font-size:25px'>Learn English </span>
      </a>
      </div>




      <nav id="nav-bar">
        <ul>
          <li><span id="link-1" class="nav-link">
          <?php
    if(isset($_COOKIE['username'])){
      echo "Xin chào ".$_COOKIE['username'];
    } else {
      header("location:signin.php");
    }
    ?>
          </span></li>
          <li><span id="link-2" class="nav-link">
          <?php
          $user = $_COOKIE['username'];
          $sql = "SELECT diem from tai_khoan WHERE username= '{$user}'";
          $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
          ?>
          
          </span></li>
          <li><a id="link-3" class="nav-link" style="cursor:pointer;" href="signout.php">Đăng xuất</a></li>
        </ul>
      </nav>
  </div>
    
    <div id="container">
    
    
      <div id="box-container">
          <div id="searchbar">
          <form action="index.php" method="get" id="formSearch">

              <input type="text" id="search" name="search" value = <?php if(isset( $_GET['search'])) echo $_GET['search']?>>
              
              <select id="select" id="select" name="select" value = <?php echo $_GET['select']?>>
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

              <input type="submit" value="Tìm kiếm">
            </form>
        
          </div>
     
        <?php  


            //get user_id
            $sqlSelectUserId = "SELECT user_id from tai_khoan where username = '{$user}' ";
            $userID = mysqli_fetch_array(mysqli_query($conn, $sqlSelectUserId), MYSQLI_ASSOC);

            $from = ($trang - 1)* $so_tu_mot_trang;
            
// Chưa học xếp trước, union với đã học xếp sau
            $sqlSapXepTu = "select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')
            UNION 
            select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')
            
            ";

            $ve1 = " select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            $ve2 = " select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            if(isset($_GET["select"])){
              $sqlselect = "SELECT * FROM chu_de WHERE Ten_chu_de='{$_GET["select"]}'";
              $selectsql = mysqli_fetch_array(mysqli_query($conn , $sqlselect), MYSQLI_ASSOC);/// sua lai ten cot 'Ten chu de' -> Ten_chu_de
              
              //echo "selectsql = ".$selectsql['ID_chude'];

              $select = " and ID_chude = '{$selectsql['ID_chude']}' ";///// chua xong
            }
            else{
              $select="";
            }


            if(empty($_GET["search"])){
              $search="";
            }
            else{
              $search =  " and ((SELECT LOCATE('{$_GET['search']}', Tu_vung)) > 0 or (SELECT LOCATE('{$_GET['search']}' , Nghia)) > 0) ";
            }

            //Override
            $sqlSapXepTu= $ve1.$select.$search." UNION ".$ve2.$select.$search;
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
           echo "<div class='box'>
           <div class='box-1'><img src='img/".$bang_tu_vung['url_hinhanh']."'></div>
           <div class='box-2'>".$bang_tu_vung['Tu_vung']."</div>
           <div class='box-3'>".$bang_tu_vung['Phat am']."</div>
           <div class='box-4'>".$bang_tu_vung['Nghia']."</div>
           <div class='box-5'>Đọc</div>
            <div class='box-6' id ='".$word_id."'".status($word_id)."</div></div>";
            }   
             ?>
        
        
      </div>
     
      <div id="phan_trang">
        <div id="page_text">Trang</div>
        <div id="pages">
            <?php 
  
            $query_tong = $sqlSapXepTu;
  
            $execute_tong = mysqli_query($conn, $query_tong);
            $tong_so_tu = mysqli_num_rows($execute_tong);
            $so_trang = ceil($tong_so_tu/$so_tu_mot_trang);
            for($i=1; $i<=$so_trang; $i++){
               
              if(isset($_GET['select']) && isset($_GET['search'])){
                echo "<div class='menu'><a href='index.php?search={$_GET['search']}&select={$_GET['select']}&trang=$i'> $i</a></div>  ";
               }
              else if(isset($_GET['select'])){
                echo "<div class='menu'><a href='index.php?search={$_GET['search']}&trang=$i'> $i</a> </div> ";
              }
              else{
                echo "<div class='menu'><a href='index.php?trang=$i'> $i</a> </div> ";
              }
              }
            
            
            ?>
        </div>
            

   
    </div>
    
<script>

function updatePoint(){
      $(document).ready(function(){
          var url = "updatepoint.php?t=" + Math.random();
          var data = {"a" : "b"};
          $("#link-2").load(url,data);
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
            action: "call_this",
            word_id: id
          }
          

        })
        await updatePoint(); 
            
}

$(document).ready(function(){
      $(".box-6").click(function(){
        if($(this).text() == "Học"){
          console.log($(this).attr('id'));
          addPoint($(this).attr('id'));
          this.style = "background-color: pink; user-select: none";
          this.innerHTML = "Đã học";
          $(this).unbind("click");
          }
          else {
            
            $(this).unbind("click");
          }
        });
      
})
</script>
<script src="index.js"></script>

  </body>
</html>
