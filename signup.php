<?php
require 'dbconn.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Đăng ký</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
      body{ background-image: linear-gradient( 115deg, #3aa55b, rgba(136, 136, 206, 0.7) );}
      a{
          text-decoration: none;
      }
      #container {
          max-width: 600px;
      }
      .field-icon {
    float: right;
    margin-left: -25px;
    margin-top: -25px;
    position: relative;
    z-index: 99;
    }
       </style>
    </head>
    <body>
        <div id="container" class="container pt-5 bg-dark text-white vh-100 w-70">
            <div id="header" class="container">
            <h4 class="font-weight-bold text-center">Đăng ký</h4>
            </div>
            <div id="signUpFormContainer" class="container pt-3">
                <form id="signUpForm" method="POST" action="signup.php">
                    <div class="form-group">
                    <label for="username" class="control-label">Tên đăng nhập</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required><br/>
                    </div>
                    <div class="form-group">
                    <label for="password" class="control-label">Mật khẩu</label>
                    
                    <input class="form-control" type="password" id="password" name="password" required>
                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span><br />
                    </div>
                    <div class="form-group">
                    <label for="repassword">Nhập lại mật khẩu</label>
                    <input class="form-control" type="password" id="repassword" name="repassword" required><br />
                    </div>
                    <div class="form-group">
                    <input type="checkbox" onclick= toggle()><span class="pl-1">Hiện mật khẩu</span>
                    </div>
                    <div class="container text-center">
                    <button class="btn btn-primary" type="submit" form="signUpForm" id="submitBtn" value="submit" name="submit" >Đăng ký</button>
                    </div>
                </form>
                <div id="guessSignUp" class="text-center" style = "margin-top:20px;">
                    <span>
                        <?php 

        if(isset($_POST['username'])) $username = addslashes($_POST['username']);


        if(isset($_POST['password'])) $password = addslashes($_POST['password']);
        if(isset($_POST['repassword'])) $repassword = addslashes($_POST['repassword']);
        //$password = md5($password);
        $pattern = '/^.{3,20}$/';

        if(isset($_POST['submit'])){

                if(!preg_match($pattern, $username)){
                    echo "Tài khoản cần có 3-20 ký tự";
                }
                else if(!preg_match($pattern, $password)){
                    echo "Mật khẩu cần có 3-20 ký tự";
                }
                else if ($password != $repassword){
                    echo "Nhập lại mật khẩu không đúng";
                }
                else if (mysqli_num_rows(mysqli_query($conn,"SELECT username FROM tai_khoan WHERE username='$username'")) > 0){
                    echo "Tên đăng nhập này đã có người dùng.";
                }
                
                else {

                $hash_password = password_hash($password, PASSWORD_DEFAULT); // mã hóa password 

                $addNewUser = "
                INSERT INTO tai_khoan (
                username,
                password
                )
                VALUE (
                '{$username}',
                '{$hash_password}'
                )";
                $a = mysqli_query($conn, $addNewUser);
                if($a) echo "Đăng ký thành công! Hãy <a href='signin.php'> đăng nhập</a>";
                }
        }
                        
                        ?>
                    </span>
                </div>
            </div>
        </div>
    <script>
    function toggle() { 
            var password = document.getElementById("password"); 
            var repassword = document.getElementById("repassword"); 
            if (password.type === "password") { 
                password.type = "text"; 
                repassword.type = "text";
            } 
            else { 
                password.type = "password"; 
                repassword.type = "password";
            } 
        } 
    </script>
    </body>
    
</html>