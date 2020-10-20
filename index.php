<?php
require 'dbconn.php';
?>
<!DOCTYPE html> 
<html>
<head>
    <meta charset="utf-8">
    <title>Test page</title>
    <link href="style.css" rel="stylesheet">
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
         
          echo "Điểm: ".$diem['diem'];
          
          ?>
          
          </span></li>
          <li><a id="link-3" class="nav-link" style="cursor:pointer;" href="signout.php">Đăng xuất</a></li>
        </ul>
      </nav>
    </header>
    
    <div id="container">
    
    
      
      <div id="box-container">
        <div class="box">
            <div class="box-1"></div>
            <div class="box-2">Từ vựng</div>
            <div class="box-3">cách đọc</div>
            <div class="box-4">Chim thiên nga</div>
            <div class="box-5">Đọc</div>
            <div class="box-6">Chưa học</div>
        </div>
        <div class="box">
            <div class="box-1"></div>
        </div>
        <div class="box">
            <div class="box-1"></div>
        </div>
      </div>
   
    </div>
  </body>
</html>
