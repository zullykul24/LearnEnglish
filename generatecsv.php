<?php

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
function generateRanInt($min, $max){
    return rand($min, $max);
}

// open the file "demosaved.csv" for writing
$file = fopen('C:\Users\zully\Desktop\read.csv', 'w');
 
// save the column headers
fputcsv($file, array('ID_tu','Tu_vung','Phat_am','Nghia','ID_chude','ID_dokho','url_hinhanh'));
 
// Sample data. This can be fetched from mysql too
$data = array(
array('\N', generateRandomString(generateRanInt(4,10)), generateRandomString(6) , generateRandomString(6), generateRanInt(1,5), generateRanInt(1,3), 'LearnEngLish.jpg'),
array('\N', generateRandomString(6), generateRandomString(6) , generateRandomString(6), generateRanInt(1,5), generateRanInt(1,3), 'LearnEngLish.jpg'),
array('\N', generateRandomString(6), generateRandomString(6) , generateRandomString(6), generateRanInt(1,5), generateRanInt(1,3), 'LearnEngLish.jpg'),
array('\N', generateRandomString(6), generateRandomString(6) , generateRandomString(6), generateRanInt(1,5), generateRanInt(1,3), 'LearnEngLish.jpg')

);
for($i = 0;$i<100000;$i++){
    array_push($data, array('\N', generateRandomString(6), generateRandomString(6) , generateRandomString(6), generateRanInt(1,5), generateRanInt(1,3), 'LearnEngLish.jpg') );
}
 
// save each row of the data
foreach ($data as $row)
{
fputcsv($file, $row);
}
 
// Close the file
fclose($file);
?>