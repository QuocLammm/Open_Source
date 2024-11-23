<?php
include('includes/connectSQL.php');

if (isset($_POST['change_password'])) {
    $username = $_POST['username'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Xác minh mật khẩu mới
    if ($newPassword !== $confirmPassword) {
        $errorMessage = "Mật khẩu xác nhận không khớp!";
    } elseif (strlen($newPassword) < 10) {
        $errorMessage = "Mật khẩu mới phải ít nhất 10 ký tự!";
    } elseif (!preg_match('/[A-Z]/', $newPassword)) {
        $errorMessage = "Mật khẩu mới phải có ít nhất một chữ cái viết hoa!";
    } elseif (!preg_match('/[\W_]/', $newPassword)) {
        $errorMessage = "Mật khẩu mới phải có ít nhất một ký tự đặc biệt!";
    } else {
        // Kiểm tra mật khẩu cũ
        $query = mysqli_query($conn, "SELECT * FROM users WHERE AccountName='$username' AND Password='$oldPassword'");
        if (mysqli_num_rows($query) == 0) {
            $errorMessage = "Mật khẩu cũ không chính xác!";
        } else {
            // Cập nhật mật khẩu mới
            $updateQuery = mysqli_query($conn, "UPDATE users SET Password='$newPassword' WHERE AccountName='$username'");
            if ($updateQuery) {
                // Chuyển hướng đến trang login
                header('Location: login.php');
                exit();
            } else {
                $errorMessage = "Đã xảy ra lỗi, vui lòng thử lại!";
            }
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
            height: 660px;
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
                <div class="inputbox">
                    <input type="text" name="username" required>
                    <span>Username</span>
                    <i></i>
                </div>
                <div class="inputbox">
                    <input type="password" name="old_password" required>
                    <span>Old Password</span>
                    <i></i>
                </div>
                <div class="inputbox">
                    <input type="password" name="new_password" required>
                    <span>New Password</span>
                    <i></i>
                </div>
                <div class="inputbox">
                    <input type="password" name="confirm_password" required>
                    <span>Confirm New Password</span>
                    <i></i>
                </div>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>
    </div>
</body>

</html>

