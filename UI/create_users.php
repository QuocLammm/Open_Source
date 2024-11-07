<?php
include("includes/session_user.php");

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize an error variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = mysqli_real_escape_string($conn, $_POST['fullname']);
    $userName = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $userCategoryID = mysqli_real_escape_string($conn, $_POST['user_category']);
    $accountName = mysqli_real_escape_string($conn, $_POST['accountname']);
    $userImage = $_FILES['image']['name']; // Get the uploaded file name
    

    // Check for required fields
    if (empty($fullName) || empty($userName) || empty($password) || empty($phoneNumber) || empty($userCategoryID) || empty($accountName)) {
        $error = "Vui lòng điền tất cả các trường bắt buộc.";
    } else {
        // Validate and move uploaded image
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], "images/users/" . $userImage)) {
                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO users (FullName, Username, Password, PhoneNumber, Gender, UserImage, UserCategoryID, AccountName) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $fullName, $userName, $password, $phoneNumber, $gender, $targetPath, $userCategoryID, $accountName);

                if ($stmt->execute()) {
                    header("Location: index_users.php?success=1"); // Redirect on success
                    exit();
                } else {
                    $error = "Lỗi khi thêm người dùng: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Có lỗi khi tải lên hình ảnh.";
            }
        } else {
            $error = "Lỗi khi tải lên hình ảnh: " . $_FILES['image']['error'];
        }
    }
}

// Display error message if there is one
if (!empty($error)) {
    echo "<p class='text-danger'>$error</p>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng mới</title>
    <link rel="stylesheet" href="../UI/css/bootstrap.min.css">
</head>
<style>
    .container {
        max-width: 900px;
        margin-top: 20px;
    }
    .form-section {
        padding: 10px;
        margin: 70px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    .btnDelete {
        cursor: pointer;
    }
    .pagination {
        display: flex;
        justify-content: center; /* Căn giữa các liên kết */
        gap: 10px; /* Tạo khoảng cách giữa các liên kết */
    }
    .pagination a {
        text-decoration: none; /* Bỏ gạch chân cho liên kết */
        padding: 8px 12px; /* Thêm padding cho các liên kết */
        border: 1px solid #007bff; /* Đường viền cho các liên kết */
        border-radius: 5px; /* Bo góc cho các liên kết */
        color: #007bff; /* Màu chữ */
    }
    .pagination a:hover {
        text-decoration: none; /* Bỏ gạch chân cho liên kết */
        background-color: #007bff; /* Màu nền khi hover */
        color: white; /* Màu chữ khi hover */
    }
    .pagination strong {
        color: red; /* Màu chữ cho trang hiện tại */
        border: 1px solid #007bff; /* Đường viền cho trang hiện tại */
        padding: 8px 12px; /* Padding tương tự như các liên kết khác */
        border-radius: 5px; /* Bo góc giống nhau */
    }
</style>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm người dùng mới</h3>
            <a href="index_users.php" class="btn btn-primary mb-2">
                <i class="ti-arrow-right"></i> Quay lại
            </a>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fullname">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Tên người dùng (Viết liền không dấu) <span class="text-danger">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Male">Nam</option>
                            <option value="Female">Nữ</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_category">Tên loại người dùng <span class="text-danger">*</span></label>
                        <select id="user_category" name="user_category" class="form-control" required>
                            <?php
                            // Fetch user categories for dropdown
                            $categoryQuery = "SELECT UserCategoryID, UserCategoryName FROM UserCategories";
                            $categoryStmt = $conn->query($categoryQuery);
                            while ($row = $categoryStmt->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['UserCategoryID']) . "'>" . htmlspecialchars($row['UserCategoryName']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh <span class="text-danger">*</span></label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="accountname">Tên tài khoản <span class="text-danger">*</span></label>
                        <input type="text" id="accountname" name="accountname" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../UI/js/bootstrap.bundle.min.js"></script>
</body>
</html>
