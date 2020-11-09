
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
<html lang="en">
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







<!--------------------------------------------------------->

      <div id="searchbar">
        <form action="result.php" method="get">

          <input type="text" id="ahihi" name="search">
          
          <select id="select" name="select">
            <option  style="display:none" disabled selected value> -- Chon mot chu de -- </option>
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

          <input type="submit" value="Tim kiem">
        </form>
      </div>

        








<!----------------------------------------------------------->








      <nav id="nav-bar">
        <ul>
          <li><span id="link-1" class="nav-link">
          <?php
    if(isset($_COOKIE['username'])){    // is set xem biến có null hay koko
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
          $diem = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);  // lấy điểm 
          
          echo "Điểm: ".$diem['diem'];  
          ?>
          
          
          </span></li>
          <li><a id="link-3" class="nav-link" style="cursor:pointer;" href="signout.php">Đăng xuất</a></li>
        </ul>
      </nav>
    </header>
    



    <div id="container">
    
    
      
      <div id="box-container">
      <!-- Cái đầu mẫu thôi, sau này xoá đi-->
        <!-- <div class="box">
            <div class="box-1">
            <img src="img/ga.jpg">
            </div>
            <div class="box-2">Từ vựng</div>
            <div class="box-3">cách đọc</div>
            <div class="box-4">Chim thiên nga</div>
            <div class="box-5">Đọc</div>
            <div class="box-6">Chưa học</div>
        </div> -->
        <?php  


            //get user_id
            $sqlSelectUserId = "SELECT user_id from tai_khoan where username = '{$user}' ";
            $userID = mysqli_fetch_array(mysqli_query($conn, $sqlSelectUserId), MYSQLI_ASSOC);

            $from = ($trang - 1)* $so_tu_mot_trang;
        //  //////////////////////////  
            

///////////////////////////////////////
// Chưa học xếp trước, union với đã học xếp sau
            $sqlSapXepTu = "select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')
            UNION 
            select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung')
            
            ";
         /*   select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = 7 order by 'Tu_vung')
            and ID_chude = 1 
            and ((SELECT LOCATE("g", Tu_vung)) > 0 or (SELECT LOCATE("g", Nghia)) > 0)
            UNION 
            select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = 7 order by 'Tu_vung')
            and ID_chude = 1 
            and ((SELECT LOCATE("g", Tu_vung)) > 0 or (SELECT LOCATE("g", Nghia)) > 0)*/
//////////
            $ve1 = " select * from tu_vung where ID_tu not in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            $ve2 = " select * from tu_vung where ID_tu in 
            (select ID_tu from user_word WHERE user_id = '{$userID['user_id']}' order by 'Tu_vung') ";

            if(isset($_GET["select"])){
              $sqlselect = "SELECT * FROM chu_de WHERE Ten_chu_de='{$_GET["select"]}'";
              $selectsql = mysqli_fetch_array(mysqli_query($conn , $sqlselect), MYSQLI_ASSOC);/// sua lai ten cot 'Ten chu de' -> Ten_chu_de
              
              echo "selectsql = ".$selectsql['ID_chude'];

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
            echo $sqlSapXepTu;

///////////////

            $sqlDanhSachTu = $sqlSapXepTu." limit $from, $so_tu_mot_trang";
            $danhSachTu = mysqli_query($conn, $sqlDanhSachTu);




            /////////////////////////////////////////////////////
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

      <
      <div id="phan_trang">
      <!--------------------------- cần sửa lại --->
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




















<?php/* var_dump($_GET);*/ ?>
<br>
<?php 
/*if(isset($_GET["select"]))
  echo "select duoc set";
else echo "select ko duoc set";
if(empty($_GET["search"]))
  echo "search empty";
else echo "search ko empty";

 */

?>

<script>

console.log(a);
    document.getElementsByName("search").value= a ;
</script>

</body>
</html>