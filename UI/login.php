<?php
include("includes/connectSQL.php");
if (isset($_POST['login'])) {
    $adminuser = $_POST['username'];
    $password = $_POST['password'];

    // Password validation
    if (strlen($password) < 10) {
        $errorMessage = "Mật khẩu không được ngắn quá 10 ký tự!";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errorMessage = "Mật khẩu phải có ít nhất một chữ cái viết hoa!";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $errorMessage = "Mật khẩu phải có ít nhất một kí tự đặc biệt!";
    } else {
        $query = mysqli_query($conn, "SELECT UserID FROM users WHERE AccountName='$adminuser' AND Password='$password'");
        $num_rows = mysqli_num_rows($query);

        if ($num_rows > 0) {
            $ret = mysqli_fetch_array($query);

            // Kiểm tra nếu UserID khác 1
            if ($ret['UserID'] != 1) {
                // Nếu UserID không phải là 1, chuyển hướng về trang dashboard
                setcookie('UserID', $ret['UserID'], time() + 36000, "/");
                header('location:dashboard.php');
                exit();
            } else {
                // Nếu UserID là 1, chuyển hướng về trang quản trị
                setcookie('UserID', $ret['UserID'], time() + 36000, "/");
                header('location:index_admin.php');
                exit();
            }
        } else {
            $errorMessage = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        @import url("https://font.googleapis.com/css2?family=Poppons:wght@400;500;500&display=swap");

        *{
            margin: 0;
            box-sizing: border-box;
        }

        body{
            margin: 0;
            padding: 0;
            display: grid;
            place-content: center;
            justify-content: center;
            text-align: center;
            height: 100vh;
            width: 100%;
            background: #23242a;
            overflow: hidden;
            color: white;

        }

        .box{
            position: relative;
            width: 480px;
            height: 620px;
            background: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
            font-family: "Poppins";
            --color: #45f3ff;
            text-align: center;
        }

        .box::before, 
        .box::after{
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg, transparent, var(--color), var(--color));
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
        }

        .box::after{
            animation-delay: -3s ;
        }

        @keyframes animate{
            0%{
                transform: rotate(0deg);
            }

            100%{
                transform: rotate(360deg);
            }
        }

        .form{
            position: absolute;
            background: #28292d;
            z-index: 10;
            inset: 2px;
            border-radius: 8px;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        .inputbox {
            position: relative;
            width: 300px;
            margin-top:35px;
            text-align: center;
        }
        .inputbox input{
            position: relative;
            width: 100%;
            padding: 10px 10px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            letter-spacing: 0.05em;
            z-index: 2;
            text-align: center;
        }
        .inputbox span{
            position: absolute;
            color: #8f8f8f8f;
            left: 0;
            padding: 20px 0px 10px 0px;
            font-size: 1em;
            pointer-events: none;
            letter-spacing: 0.05em;
            transform:  translateY(-10px);
            transition: 0.5s;
            text-align: ce;
        }

        .inputbox input:valid~span,
        .inputbox input:focus~span{
            color: var(--color);
            transform: translateY(-40px);
            font-size: 0.7em;
        }
        .inputbox i{
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: var(--color);
            transition: 0.5s;
            border-radius: 4px;
            pointer-events: none;
        }
        .inputbox input:valid~i,
        .inputbox input:focus~i{
            height: 40px;
        }

        .links{
            display: flex;
            text-align: right;
        }
        .links a{
            margin: 18px 0;
            font-size: 0.9em;
            text-decoration: none;
            color: #8f8f8f8f;
        }
        .links a:hover{
            color: var(--color);
        }

        button[type="submit"] {
            width: 300px;
            background: var(--color);
            border: none;
            outline: none;
            padding: 11px 25px;
            margin-top: 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            color: white;
            text-transform: uppercase;
        }

    </style>
</head>

<body>
    <div class="box">
        <div class="form">
            <img src="images/lt.jpg" alt="logo" class="w-100">

            <!-- Hiển thị thông báo lỗi -->
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Form đăng nhập -->
            <form action="" method="POST">
                <div class="container">
                    <div class="inputbox">
                        <input type="text" name="username" required>
                        <span>UserName</span>
                        <i></i>
                    </div>
                    <div class="inputbox">
                        <input type="password" name="password" required>
                        <span>Password</span>
                        <i></i>
                    </div>
                    <div class="links">
                        <a href="forgot_password.php">Forgot password?</a>
                    </div>
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

