<?php
require 'dbconn.php';
$so_tu_mot_trang = 4;
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
    <link href="style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header id="header">
      <div class="logo">
      
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
         
        //  echo "Điểm: ".$diem['diem'];
          
          ?>
          
          </span></li>
          <li><a id="link-3" class="nav-link" style="cursor:pointer;" href="signout.php">Đăng xuất</a></li>
        </ul>
      </nav>
    </header>
    
    <div id="container">
    
    
      
      <div id="box-container">
        <div class="box">
            <div class="box-1">
            <img src="img/ga.jpg">
            </div>
            <div class="box-2">Từ vựng</div>
            <div class="box-3">cách đọc</div>
            <div class="box-4">Chim thiên nga</div>
            <div class="box-5">Đọc</div>
            <div class="box-6">Chưa học</div>
        </div>
        <?php 
            $from = ($trang - 1)* $so_tu_mot_trang;
            
            $qr = "select * from tu_vung limit $from, $so_tu_mot_trang";
            $ds = mysqli_query($conn, $qr);
            
           
            while($bang_tu_vung = mysqli_fetch_array($ds)){
              $word_id = $bang_tu_vung['ID_tu'];
            
           // echo $bang_tu_vung["Tu vung"];
           echo "<div class='box'>
           <div class='box-1'><img src='img/ga.jpg'></div>
           <div class='box-2'>".$bang_tu_vung['Tu vung']."</div>
           <div class='box-3'>".$bang_tu_vung['Phat am']."</div>
           <div class='box-4'>".$bang_tu_vung['Nghia']."</div>
           <div class='box-5'>".$word_id."</div>
            <div class='box-6' id =".$word_id." >Chưa học</div>
           </div>";
            }   
             ?>
        
        
      </div>
      <div id="phan_trang">
            <?php 
            $query_tong = "select * from tu_vung";
            $execute_tong = mysqli_query($conn, $query_tong);
            $tong_so_tu = mysqli_num_rows($execute_tong);
            $so_trang = ceil($tong_so_tu/$so_tu_mot_trang);
            for($i=1; $i<=$so_trang; $i++){
                echo "<a href='index.php?trang=$i'>Trang $i</a>  ";
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
        // $(document).ready(function(){
        //   var url2 = "addpoint.php?t=" + Math.random();
        //   var data2 = {"action" : "call_this"};
        //     $(document).load(url2,data2);
           
        // });
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
          addPoint($(this).attr('id'));
          this.style = "background-color: pink; user-select: none";
          this.innerHTML = "Đã học";
          $(this).unbind("click");
        });
      
})
</script>
  </body>
</html>
