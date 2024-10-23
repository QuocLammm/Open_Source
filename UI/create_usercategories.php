<?php
// Establish the connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlcoffee"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra xem có gửi dữ liệu từ biểu mẫu không
if (isset($_POST['submit'])) {
    // Lấy dữ liệu từ biểu mẫu
    $userCategoryName = $_POST['userCategoryName'];
    $userCategoryDescription = $_POST['userCategoryDescription'];

    // Kiểm tra dữ liệu nhập vào
    if (empty($userCategoryName) || empty($userCategoryDescription)) {
        echo '<script>alert("Vui lòng nhập tất cả thông tin cần thiết.");</script>';
    } else {
        // Truy vấn để thêm loại người dùng
        $query = mysqli_query($conn, "INSERT INTO usercategories (UserCategoryName, UserCategoryDescription) VALUES ('$userCategoryName', '$userCategoryDescription')");

        // Kiểm tra kết quả truy vấn
        if ($query) {
            echo '<script>alert("Thêm loại người dùng thành công.");</script>';
            echo "<script>window.location.href ='index_usercategories.php';</script>";
            exit();
        } else {
            echo '<script>alert("Có lỗi xảy ra: ' . mysqli_error($conn) . '. Vui lòng thử lại.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm loại người dùng</title>
    <link rel="stylesheet" href="../UI/css/bootstrap.min.css">
    
</head>
<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-5">
        <h3>Thêm loại người dùng</h3>
        <a href="index_usercategories.php" class="btn btn-primary mb-2">
            <i class="ti-arrow-left"></i> Quay lại
        </a>

        <form method="post" action="">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="userCategoryName">Tên loại người dùng <span class="text-danger">*</span></label>
                        <input type="text" id="userCategoryName" name="userCategoryName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userCategoryDescription">Mô tả</label>
                        <textarea id="userCategoryDescription" name="userCategoryDescription" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-info">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../UI/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
