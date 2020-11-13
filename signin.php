<?php
require 'dbconn.php';
ob_start ();
if(isset($_COOKIE['username'])){header("location:index.php");}
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
                        if(isset($_POST['username'])) $username = addslashes($_POST['username']);
                        if(isset($_POST['password'])) $password = addslashes($_POST['password']);
                            

                        if(isset($_POST['submit'])){

                            $sql = "SELECT username, password FROM tai_khoan WHERE username='$username'";

                            
                            if (mysqli_num_rows(mysqli_query($conn, $sql)) == 0){
                                echo "Tên đăng nhập không tồn tại.";
                            }
                            else {
                                $row = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);
                                if (!password_verify($password,$row['password'])) {
                                    echo "Mật khẩu sai";
                                }
                                else {
                                    header("location:index.php", true, 301);
                                    setcookie("username", $_POST['username'], time()+3000, "/","", 0);
                                    
                                    exit;
                                }
                            }
                            
                    }
 ob_flush ();?>
                    </p>
                </div>
                    <span>Chưa có tài khoản? </span><span id="linkToSignUp"><u>
                        <a target="_blank" href="signup.php">Đăng ký ngay</a></u></span></div>
                </div>
            </div>
        
    </body>
</html>