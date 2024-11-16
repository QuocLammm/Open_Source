<?php
include('includes/connectSQL.php');
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
            // Set cookie for 1 hour
            setcookie('UserID', $ret['UserID'], time() + 3600, "/"); 
            header('location:index_admin.php');
            exit();
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo d-flex justify-content-center">
                                <img src="images/lt.jpg" alt="logo" class="w-100">
                            </div>
                            <?php if (isset($errorMessage)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>
                            <form class="pt-3" method="POST" name="login">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" placeholder="Tên tài khoản" name="username" required="true">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg"  placeholder="Mật khẩu" name="password" required="true">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="login">Đăng nhập</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
</body>

</html>
