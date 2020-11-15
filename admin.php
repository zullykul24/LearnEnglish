<?php
require 'dbconn.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="stylesheet" href="style.css?v=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
    
    </head>
    <body>
        <div class="container-fluid pt-5 pt-lg-0 bg-light  w-100">
            <div id="header" class="row text-light p-2 vh-10  bg-dark fixed-top align-items-center mb-5 ">
                <div id="logo" class="col-12 col-lg-3 pl-lg-5">
                    <a href='index.php'  >
                      <span >Learn English </span>
                    </a>
                </div>
                <div class="col-lg-1"></div>
        
                <div class="col-12 col-lg-5  pl-lg-5   ">
                      
                        <span id="name" class="header-item">
                        <?php
                        if(isset($_COOKIE['username']) && $_COOKIE['username'] == 'admin' ){
                        $admin = $_COOKIE['username'];
                        echo "Xin chao admin";
                        } else {
                        header("location:signin.php", true, 301);
                        }
                        ?>
                        </span>
                    </div>
                    
                <div class="col-12 col-lg-3 pr-lg-5 text-lg-right "> <a id="signout-text" class="header-item  " href="signout.php">Đăng xuất</a></div>
            </div>
            
            <div id="main" class="row mt-5 justify-content-center py-2 py-lg-5 px-2 mx-2 mx-lg-0 px-lg-0 ">
                <div class="col-12 text-center font-weight-bold my-3">Thêm từ mới</div>
            <form action="admin.php" method="POST" id="form-add-word" class="pt-3" enctype="multipart/form-data">
                <div class="row align-items-center py-0 py-md-2 py-lg-0 mb-3">
                <div class="col-5 col-lg-4 mt-1 ">
                    <input type="text"  id="new-word" name="new-word" class="w-100" placeholder="Nhập từ cần thêm" required>
                </div>
                <div class="col-5 col-lg-4 mt-1 ">
                    <input type="text"  id="new-word-pro" name="new-word-pro" class="w-100" placeholder="Phát âm" required>
                </div>
                <div class="col-5 col-lg-4 mt-1 ">
                    <input type="text"  id="new-word-meaning" name="new-word-meaning" class="w-100" placeholder="Nghĩa" required>
                </div>
                    </div><div class="row align-items-center py-0 py-md-2 py-lg-0 mb-3">
                <div class="col-5 col-lg-4">
                    <select id="new-word-subject" name="new-word-subject" class="w-100" >
                    <option  style="display:none" disabled selected value>
                   Chọn chủ đề
                    </option>
                    <?php

                    $sql = "SELECT * from chu_de";
                    $chu_de =  $conn->query($sql);
                    if ($chu_de->num_rows > 0) {
                    // output data of each row
                    while($row = $chu_de->fetch_assoc()) {
                        echo "<option>".$row["Ten_chu_de"]."</option>" ;
                    }
                    } else {
                    //////
                    }
                    ?>
                    </select>
                </div>
                <div class="col-4 col-lg-4 mt-2 mt-lg-0 ">
                    <select id="new-word-dif" name="new-word-dif" class="w-100"   >
                    <option  style="display:none" disabled selected value >
                   Chọn độ khó
                    </option>
                    <?php

                        $sql = "SELECT * from do_kho";
                        $do_kho =  $conn->query($sql);
                        if ($do_kho->num_rows > 0) {
                        // output data of each row
                        while($row = $do_kho->fetch_assoc()) {
                            echo "<option>".$row["Ten_do_kho"]."</option>" ;
                        }
                        } else {
                        //////
                        }
                    ?>
                    
                    </select>
                </div>
                    </div><div class = "row">
                <div class="col-12 col-lg-6">
                    <input type="file" id="img-url" name ="file" accept = "image/*">
                    </div>
                </div>
                
                <div class="col-3 col-lg-6 mt-3">
                    <img id="word-img" src="#" width="100%"  style="max-width: 200px; max-height: 200px;">
                </div>

                
                <div class="col-12 col-lg-12 mt-3 mt-lg-5 text-center">
                <input type="submit" class="btn btn-primary" name="submit-new-word" value="Thêm từ mới"></div>
              </div>
              <div class="col-12 text-center">
              <?php
              if(isset($_POST['new-word'])) $new_word = addslashes($_POST['new-word']);
              if(isset($_POST['new-word-pro'])) $new_word_pro = addslashes($_POST['new-word-pro']);
              if(isset($_POST['new-word-meaning'])) $new_word_meaning = addslashes($_POST['new-word-meaning']);
              if(isset($_POST['new-word-subject'])) $new_word_subject = addslashes($_POST['new-word-subject']);
              if(isset($_POST['new-word-dif'])) $new_word_dif = addslashes($_POST['new-word-dif']);
             
              
              if(isset($_POST['submit-new-word'])){
                if (mysqli_num_rows(mysqli_query($conn,"SELECT Tu_vung FROM tu_vung WHERE Tu_vung='$new_word'")) > 0){
                    echo "Từ này đã có trong bộ từ vựng";
                }
                else {
                    
                    $sql_get_subject_id = "SELECT ID_chude from chu_de WHERE Ten_chu_de = '{$new_word_subject}'";
                    $subject_id = mysqli_fetch_array(mysqli_query($conn, $sql_get_subject_id), MYSQLI_ASSOC);
                    $sql_get_dif_id = "SELECT ID_dokho from do_kho WHERE Ten_do_kho = '{$new_word_dif}'";
                    $dif_id = mysqli_fetch_array(mysqli_query($conn, $sql_get_dif_id), MYSQLI_ASSOC);
                     
                    $file = $_FILES['file'];
                    $file_name = $_FILES['file']['name'];
                    $file_tmp_name = $_FILES['file']['tmp_name'];
                    $file_size = $_FILES['file']['size'];
                    $file_error = $_FILES['file']['error'];
                    $file_type = $_FILES['file']['type'];
                    $file_extension = explode('.', $file_name);
                    $file_actual_extension = strtolower(end($file_extension));
                    $allowed = array('jpg', 'jpeg', 'png');
                    if(in_array($file_actual_extension, $allowed))
                    {
                        if($file_error === 0){
                            if($file_size < 200000){
                                $file_name_new = uniqid('', true).'.'.$file_actual_extension;
                                $target_file = "img/".$file_name_new;
                                move_uploaded_file($file_tmp_name, $target_file);
                            }
                            else {
                                echo "Dung lượng file không được quá 200KB.";
                            }
                        }
                        else {
                            echo "Có lỗi xảy ra. Vui lòng thử lại.";
                        }
                    }
                    else {
                        echo "Chỉ chấp nhận file jpg, jpeg, png.";
                    }
                    

                    ////
                    $sql_insert_new_word = "INSERT into tu_vung(Tu_vung, Phat_am, Nghia, ID_chude, ID_dokho, url_hinhanh)
                    VALUES('{$new_word}', '{$new_word_pro}', '{$new_word_meaning}', '{$subject_id['ID_chude']}', '{$dif_id['ID_dokho']}', '{$file_name_new}')";
                    mysqli_query($conn, $sql_insert_new_word);
                    echo "Thêm từ mới thành công!";
                }
              }
              
              
              
              
              ?></div>
              </form>
              
              <div class="col-12 text-center font-weight-bold mt-5 mb-3">Thêm độ khó</div>
              <form class="row justify-content-center mx-0  px-lg-5 mx-lg-5" method="POST">
                  <input class="col-4 col-lg-2 mr-5" type="text" name="new-dif" placeholder="Nhập tên độ khó" required>
                  <input class="col-4 col-lg-2 ml-5" type="number" name="dif-point" placeholder="Nhập số điểm" required>
                  <div class="col-12 col-lg-12 mt-3 mt-lg-3 text-center">
                    <input type="submit" class="btn btn-primary " name="submit-new-dif" value="Thêm độ khó"></div>
              </form>
              <!-- Xử lý thêm độ khó -->
              <div class="col-12 text-center mt-3">
              <?php
                     if(isset($_POST['new-dif']))$new_dif = addslashes($_POST['new-dif']);
                     if(isset($_POST['dif-point']))$dif_point = addslashes($_POST['dif-point']);
                     if(isset($_POST['submit-new-dif'])){
                        if (mysqli_num_rows(mysqli_query($conn,"SELECT Ten_do_kho FROM do_kho WHERE Ten_do_kho='$new_dif'")) > 0){
                            echo "Độ khó này đã tồn tại.";
                        }
                        else if(mysqli_num_rows(mysqli_query($conn,"SELECT Diem FROM do_kho WHERE Diem='$dif_point'")) > 0){
                            echo "Mức điểm này đã tồn tại.";
                        }
                        else{
                            $sql_insert_new_dif = "INSERT into do_kho(Ten_do_kho, Diem) VALUES('{$new_dif}', '{$dif_point}')";
                            mysqli_query($conn, $sql_insert_new_dif);
                            echo "Thêm độ khó thành công!";
                        }
                     }
                     
              ?>
              </div>
             
              <div class="col-12 text-center font-weight-bold mt-5 mb-3">Thêm chủ đề</div>
              <form class="row justify-content-around" method="POST">
                <input class="col-lg-2 col-5 mt-3 mt-lg-0" name = "new-subject" type="text" placeholder="Nhập tên chủ đề">
                
                <div class="col-12 col-lg-12 mt-3 mt-lg-3 text-center">
                    <input type="submit" class="btn btn-primary " name="new-subject-submit" value="Thêm chủ đề"></div>
            </form>
            <!-- Xử lý thêm chủ đề -->
            <div class="col-12 text-center my-4">
            <?php
             if(isset($_POST['new-subject'])) $new_subject = addslashes($_POST['new-subject']);
             if(isset($_POST['new-subject-submit'])){
                if (mysqli_num_rows(mysqli_query($conn,"SELECT Ten_chu_de FROM chu_de WHERE Ten_chu_de='$new_subject'")) > 0){
                    echo "Chủ đề này đã tồn tại.";
                }
                else {
                    $sql_insert_new_subject = "INSERT into chu_de(Ten_chu_de) VALUES('{$new_subject}')";
                    mysqli_query($conn, $sql_insert_new_subject);
                    echo "Thêm chủ đề thành công!";
                }
             }
            ?>
            </div>
              </div>
            </div>
        <script>
            function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#word-img').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
        }
    
        $("#img-url").change(function(){
        readURL(this);
        });
        

        ///prevent resubmit when refresh page
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        </script>
    </body>
    </html>