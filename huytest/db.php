<?php 
$hostname = 'localhost:3307';
$username = 'root';
$password = '';
$dbname = "test";
$conn = mysqli_connect($hostname, $username, $password,$dbname);
if (!$conn) {
 die('Không thể kết nối: ' . mysqli_error($conn));
 exit();
}
?>