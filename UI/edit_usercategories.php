<?php
include("includes/session_user.php");
include("includes/UsersCategoriesController.php"); // Bao gồm lớp UserCategoriesController

$controller = new UserCategoriesController();
$userCategoryID = null;
$userCategoryName = '';
$userCategoryDescription = '';

// Lấy UserCategoryID từ URL và kiểm tra hợp lệ
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userCategoryID = $_GET['id'];
    
    // Lấy thông tin loại người dùng
    $category = $controller->getCategory($userCategoryID);
    if ($category) {
        $userCategoryName = $category['name'];
        $userCategoryDescription = $category['description'];
    } else {
        echo "Không tìm thấy loại người dùng.";
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
    if ($controller->updateCategory($userCategoryID, $updatedName, $updatedDescription)) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='index_usercategories.php';</script>";
    } else {
        echo "Lỗi khi cập nhật bản ghi: " . $conn->error;
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
    <title>Chỉnh sửa loại người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container{
            max-width: 900px;
            margin-top: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Space between columns */
        }
        .form-column {
            flex: 1; /* Equal width columns */
        }
        .text-center {
            text-align: center;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }

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
</head>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-5">
        <form method="post" class="form-section">
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
</body>

</html>