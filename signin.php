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
       </style>
    </head>
    <body>
        <div id="container" class="container pt-5 bg-dark text-white vh-100 w-70">
            <div id="header" class="container">
                <h4 class="font-weight-bold text-center">Đăng nhập</h4>
            </div>
            <div id="loginFormContainer" class="container pt-5">
            <form method="POST" action = "signin.php">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required><br/>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" id="password" name="password" required><br />
                    </div>
                    <div class="container text-center">
                    <button type="submit" id="submitBtn" value="submit" name="submit" class="btn btn-primary ">Đăng nhập</button>
                    </div>
                    
            </form>
                <div id="guessSignUp" class="container pt-3">
                    <div id="message" class="text-center text-danger">
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
                <div class="text-center">
                    <span>Chưa có tài khoản? </span><span id="linkToSignUp">
                        <a  href="signup.php">Đăng ký ngay</a></span></div>
                        </div>
                </div>
            </div>
        
    </body>
</html>