<?php 
require 'dbconn.php';

if($_POST['action'] == 'update_point'){
    $user = $_COOKIE['username'];
    $sql = "SELECT diem from tai_khoan WHERE username= '{$user}'";
    $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
   
   echo "Điểm: ".$diem['diem'];
}

?>