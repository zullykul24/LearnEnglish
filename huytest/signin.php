<?php
require 'dbconn.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Đăng nhập</title>
        <meta charset="utf-8">
        <link href="signin.css" rel="stylesheet">
    </head>
    <body>
        <div id="container">
            <div id="header">
                <p>Đăng nhập</p>
            </div>
            <div id="loginFormContainer" style = "height:300px;">
                <form id="loginForm" method="POST" action = "signin.php">
                    <div class="lineForm">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required><br/>
                    </div>
                    <div class="lineForm">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required><br />
                    </div>
                    <div class="lineForm">
                    <button type="submit" form="loginForm" id="submitBtn" value="submit" name="submit">Đăng nhập</button>
                    </div>
                </form>
                <div id="guessSignUp">
                    <div id="message">
                    <p>
                        <?php 
                        if(isset($_POST['username'])) $username = addslashes($_POST['username']);   // thêm dấu '\' trước các '', "", \, NULL
                        if(isset($_POST['password'])) $password = addslashes($_POST['password']);

                        if(isset($_POST['submit'])){

                            $sql = "SELECT username, password FROM tai_khoan WHERE username='$username'";

                            
                            if (mysqli_num_rows(mysqli_query($conn, $sql)) == 0){       //  đếm số hàng
                                echo "Tên đăng nhập không tồn tại.";
                            }
                            else {
                                $row = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);     // lấy ra thông tin truy vấn lưu dưới dạng array MYSQLI_ASSOC để hiển thị theo dạng liên kết, MYSQLI_NUM để theo số VD: row[username] , row[1]
                                if ($password != $row['password']) {        // vì array chỉ có 2 biến nên viết được như này
                                    echo "Mật khẩu sai";
                                }
                                else {
                                    setcookie("username", $_POST['username'], time()+3000, "/","", 0);      //tạo cookie, hết hạn sau 3000s 
                                                                                                            // $_POST : lấy thông tin từ form có method="post"
                                    header("location:search.php");       // chuyển sang index 
                                                                        // mình chuyển sang search 
                                    
                                }
                            }
                            
                    }
                        
                        
                        
                        
                        ?>
                    </p>
                </div>
                    <span>Chưa có tài khoản? </span><span id="linkToSignUp"><u>
                        <a target="_blank" href="signup.php">Đăng ký ngay</a></u></span></div>
                </div>
            </div>
        
    </body>
</html>