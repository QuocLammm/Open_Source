<?php
include("includes/session_user.php"); // Kết nối cơ sở dữ liệu

// Xử lý dữ liệu từ biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drinkCategoryName = isset($_POST['drinkCategoryName']) ? trim($_POST['drinkCategoryName']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Kiểm tra xem tên loại đồ uống có trống không
    if (empty($drinkCategoryName)) {
        $error_message = "Tên loại đồ uống không được để trống.";
    } else {
        // Kiểm tra xem tên loại đồ uống đã tồn tại trong cơ sở dữ liệu chưa
        $checkQuery = $conn->prepare("SELECT COUNT(*) FROM DrinkCategories WHERE DrinkCategoryName = ?");
        $checkQuery->bind_param("s", $drinkCategoryName);
        $checkQuery->execute();
        $checkQuery->bind_result($count);
        $checkQuery->fetch();
        $checkQuery->close();

        // Nếu loại đồ uống đã tồn tại, thông báo lỗi
        if ($count > 0) {
            $error_message = "Tên loại đồ uống đã tồn tại. Vui lòng chọn tên khác.";
        } else {
            // Nếu chưa tồn tại, thực hiện thêm loại đồ uống mới
            $stmt = $conn->prepare("INSERT INTO DrinkCategories (DrinkCategoryName, DrinkCategoryDescription) VALUES (?, ?)");
            $stmt->bind_param("ss", $drinkCategoryName, $description);

            if ($stmt->execute()) {
                header("Location: create_drink_category.php?success=1");
                exit();
            } else {
                $error_message = "Lỗi khi thêm loại đồ uống: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Loại Đồ Uống</title>
    <link rel="stylesheet" href="../UI/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
    <style>
        h3 {
            margin-bottom: 20px;
        }
        .form-section {
            max-width: 700px;
            margin: 50px auto;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .btn-info {
            background-color: #DEB887;
            color: white;
        }
        .btn-back {
            background-color: #6c63ff;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>

    <!-- SweetAlert2 Notification -->
    <script>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        Swal.fire({
            title: 'Thành công!',
            text: 'Thêm loại đồ uống thành công!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index_drinkcategories.php';
        });
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        Swal.fire({
            title: 'Lỗi!',
            text: <?= json_encode($error_message) ?>,
            icon: 'error',
            confirmButtonText: 'OK'
        });
        <?php endif; ?>
    </script>

    <div class="container">
        <div class="form-section">
            <h3>Thêm Loại Đồ Uống</h3>
            <a href="index_drinkcategories.php" class="btn btn-primary mb-3">
                <i class="ti-arrow-left"></i> Quay lại
            </a>
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="drinkCategoryName">Tên loại đồ uống <span class="text-danger">*</span></label>
                            <input type="text" id="drinkCategoryName" name="drinkCategoryName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-info mt-3">Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../UI/js/bootstrap.bundle.min.js"></script>
</body>
</html>
