<?php 
require 'dbconn.php';


if($_POST['action'] == 'sub_point' && $_POST['word_id'] != ''){
    $usera = $_COOKIE['username'];

    //get word_id
    $wordID = $_POST['word_id'];

    $sqlPointOfWord = "SELECT Diem from do_kho d join tu_vung t 
    on d.ID_dokho = t.ID_dokho where t.ID_tu = '{$wordID}' ";

    $point = mysqli_fetch_array(mysqli_query($conn, $sqlPointOfWord), MYSQLI_ASSOC);


     $sqlUpdateAddPoint = "UPDATE tai_khoan SET diem = diem - {$point['Diem']} WHERE username = '{$usera}'";
     mysqli_query($conn, $sqlUpdateAddPoint);

     // get user_id
     $sqlSelectUserId = "SELECT user_id from tai_khoan where username = '{$usera}' ";
     $userID= mysqli_fetch_array(mysqli_query($conn, $sqlSelectUserId), MYSQLI_ASSOC);

     

$sqlDelete = "DELETE FROM user_word WHERE user_id ={$userID['user_id']} AND ID_tu = {$wordID} ";
     $sqlInsert = "INSERT INTO user_word(user_id, ID_tu) VALUES ({$userID['user_id']}, {$wordID})";
     mysqli_query($conn, $sqlDelete);
}

?>