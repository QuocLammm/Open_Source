<?php
include("includes/session_user.php"); // Kết nối cơ sở dữ liệu

// Xử lý form submit để thêm loại người dùng mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userCategoryName = htmlspecialchars($_POST['userCategoryName']);
    $userCategoryDescription = htmlspecialchars($_POST['userCategoryDescription']);

    // Truy vấn chèn loại người dùng mới vào bảng UserCategories
    $stmt = $conn->prepare("INSERT INTO UserCategories (UserCategoryName, UserCategoryDescription) VALUES (?, ?)");
    $stmt->bind_param("ss", $userCategoryName, $userCategoryDescription);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm loại người dùng thành công!'); window.location.href='index_usercategories.php';</script>";
    } else {
        echo "Lỗi khi thêm loại người dùng: " . $stmt->error;
    }

    $stmt->close();
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm loại người dùng</title>
    <link rel="stylesheet" href="../UI/css/bootstrap.min.css">
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
        .btn-info {
            background-color: #DEB887;
            color: white;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-4">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm loại người dùng</h3>
            <a href="index_usercategories.php" class="btn btn-primary mb-2">
                <i class="ti-arrow-left"></i> Quay lại
            </a>
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
                    <button type="submit" name="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../UI/js/bootstrap.bundle.min.js"></script>
</body>
</html>
