<?php
require 'dbconn.php'

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Đăng ký</title>
        <meta charset="utf-8">
        <link href="signin.css" rel="stylesheet">
    </head>
    <body>
        <div id="container">
            <div id="header">
                <p>Đăng ký</p>
            </div>
            <div id="loginFormContainer">
                <form id="loginForm" method="POST" action="signup.php">
                    <div class="lineForm">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required><br/>
                    </div>
                    <div class="lineForm">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required><br />
                    </div>
                    <div class="lineForm">
                    <button type="submit" form="loginForm" id="submitBtn" value="submit" name="submit" >Đăng ký</button>
                    </div>
                </form>
                <div id="guessSignUp">
                    <span>
                        <?php 

if(isset($_POST['username'])) $username = addslashes($_POST['username']);


if(isset($_POST['password'])) $password = addslashes($_POST['password']);
//$password = md5($password);
$pattern = '/^.{8,}$/';

if(isset($_POST['submit'])){

        if(!preg_match($pattern, $username)){
            echo "Tài khoản cần ít nhất 8 kí tự.";
        }
        else if(!preg_match($pattern, $password)){
            echo "Mật khẩu cần ít nhất 8 kí tự.";
        }
        else if (mysqli_num_rows(mysqli_query($conn,"SELECT username FROM tai_khoan WHERE username='$username'")) > 0){
            echo "Tên đăng nhập này đã có người dùng.";
        }
        else {
            $addNewUser = "
            INSERT INTO tai_khoan (
            username,
            password,
            diem
            )
            VALUE (
            '{$username}',
            '{$password}',
            0
            )";
            $a = mysqli_query($conn, $addNewUser);
            if($a) echo "Đăng ký thành công! Hãy đăng nhập";
        }
}
                        
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </body>
    
</html>