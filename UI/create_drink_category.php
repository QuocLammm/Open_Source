<?php
include("includes/session_user.php");
include("includes/DrinkCategoriesController.php"); // Bao gồm lớp điều khiển

$controller = new DrinkCategoriesController($conn);

// Kiểm tra xem có gửi dữ liệu từ biểu mẫu không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $drinkCategoryName = isset($_POST['drinkCategoryName']) ? $_POST['drinkCategoryName'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    // Kiểm tra các trường bắt buộc
    if (empty($drinkCategoryName)) {
        $error_message = "Tên loại đồ uống không được để trống.";
    } else {
        $drinkCategory = [
            'DrinkCategoryName' => $drinkCategoryName,
            'DrinkCategoryDescription' => $description
        ];

        // Gọi phương thức tạo mới
        if ($controller->create($drinkCategory)) {
            header("Location: index_drinkcategories.php?success=1");
            exit();
        } else {
            $error_message = "Lỗi khi thêm loại đồ uống.";
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
    <title>Thêm Loại Đồ Uống</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        h3 {
            margin-bottom: 20px;
        }
        .text-danger {
            color: red;
        }
        .btn-back {
            background-color: #6c63ff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
        }
        .form-group {
            margin-bottom: 15px;
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
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm Loại Đồ Uống Mới</h3>
            <a href="index_drinkcategories.php" class="btn btn-primary mb-2">
                Quay lại
            </a>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="drinkCategoryName">Tên loại đồ uống <span class="text-danger">*</span></label>
                        <input type="text" id="drinkCategoryName" name="drinkCategoryName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-info mt-2">Lưu</button>
        </form>
    </div>
</body>
</html>