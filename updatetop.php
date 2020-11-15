<?php
require 'dbconn.php';
if($_POST['top'] == 'update_top'){
    $user = $_COOKIE['username'];
    $sql = "SELECT diem from tai_khoan WHERE username= '{$user}'";
    $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
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
}?>