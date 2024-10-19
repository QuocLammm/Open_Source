<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlcoffee"; // Thay bằng tên cơ sở dữ liệu của bạn
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy UserCategoryID từ URL và kiểm tra hợp lệ
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userCategoryID = $_GET['id'];

    // Thực hiện truy vấn để lấy thông tin loại người dùng
    $query = "SELECT UserCategoryName, UserCategoryDescription FROM UserCategories WHERE UserCategoryID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userCategoryID);
        $stmt->execute();
        $stmt->bind_result($userCategoryName, $userCategoryDescription);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
        exit();
    }
} else {
    echo "No valid UserCategoryID provided!";
    exit();
}

// Xử lý form submit để lưu thông tin sau khi chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedName = htmlspecialchars($_POST['name']);  // Sử dụng htmlspecialchars để tránh XSS
    $updatedDescription = htmlspecialchars($_POST['description']);

    // Cập nhật thông tin loại người dùng
    $updateQuery = "UPDATE UserCategories SET UserCategoryName = ?, UserCategoryDescription = ? WHERE UserCategoryID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    if ($updateStmt) {
        $updateStmt->bind_param("ssi", $updatedName, $updatedDescription, $userCategoryID);
        
        if ($updateStmt->execute()) {
            echo "<script>alert('Record updated successfully'); window.location.href='index_usercategories.php';</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $updateStmt->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
}

$conn->close();
?>

<!-- Trang hiển thị form chỉnh sửa -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Đảm bảo không có tràn */
        body {
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-5">
        <form method="post" enctype="multipart/form-data" class="form-section">
        <h2 class="mb-4">Chỉnh sửa loại người dùng</h2>
            <div class="mb-3">
                <label for="name" class="form-label">Tên loại người dùng:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userCategoryName); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($userCategoryDescription); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="index_usercategories.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
