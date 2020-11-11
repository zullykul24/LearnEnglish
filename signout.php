<?php
setcookie("username", "", time()+3000, "/","", 0);
header("location:signin.php");
?>
