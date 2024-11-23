<?php
include("includes/session_user.php"); // Kết nối cơ sở dữ liệu

// Khởi tạo biến thành công
$successMessage = false;

// Lấy ID đồ uống từ URL
if (isset($_GET['id'])) {
    $drinkcateId = intval($_GET['id']);

    // Truy vấn để lấy thông tin loại đồ uống
    $sql = "SELECT * FROM drinkcategories WHERE DrinkCategoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $drinkcateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy thông tin loại đồ uống
        $drinkcate = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy loại đồ uống.";
        exit;
    }
} else {
    echo "ID không hợp lệ.";
    exit;
}

// Khởi tạo biến với giá trị mặc định
$drinkName = isset($drinkcate['DrinkCategoryName']) ? $drinkcate['DrinkCategoryName'] : '';
$drinkDescription = isset($drinkcate['DrinkCategoryDescription']) ? $drinkcate['DrinkCategoryDescription'] : '';

// Kiểm tra xem có gửi dữ liệu từ biểu mẫu không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drinkName = isset($_POST['drinkName']) ? trim($_POST['drinkName']) : '';
    $drinkDescription = isset($_POST['drinkDescription']) ? trim($_POST['drinkDescription']) : '';

    // Cập nhật thông tin loại đồ uống
    $updateSql = "UPDATE drinkcategories SET DrinkCategoryName = ?, DrinkCategoryDescription = ? WHERE DrinkCategoryID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssi", $drinkName, $drinkDescription, $drinkcateId);

    if ($updateStmt->execute()) {
        // Đặt biến successMessage = true khi cập nhật thành công
        $successMessage = true;
    } else {
        echo '<script>alert("Có lỗi xảy ra: ' . $conn->error . '. Vui lòng thử lại.");</script>';
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Loại Đồ Uống</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
            gap: 20px; /* Khoảng cách giữa các cột */
        }
        .form-column {
            flex: 1; /* Các cột có chiều rộng bằng nhau */
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
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container">
        <form method="post" enctype="multipart/form-data" class="form-section">
            <h3 class="text-left mb-4">Chỉnh Sửa Loại Đồ Uống</h3>
            
            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="drinkName">Tên Loại Đồ Uống</label>
                        <input type="text" id="drinkName" name="drinkName" value="<?php echo htmlspecialchars($drinkName); ?>" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="drinkDescription">Mô Tả</label>
                        <input type="text" id="drinkDescription" name="drinkDescription" value="<?php echo htmlspecialchars($drinkDescription); ?>" required autocomplete="off">
                    </div>                   
                </div>   
            </div>
            
            <div class="text-center btn-save">
                <button type="submit" class="btn btn-success">Cập Nhật</button>
                <a href="index_drinkcategories.php" class="btn btn-secondary">Quay Lại</a>
            </div>
        </form>
    </div>

    <script>
        // Đảm bảo rằng SweetAlert chỉ hiển thị khi cập nhật thành công
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($successMessage): ?>
                Swal.fire({
                    title: 'Cập nhật thành công!',
                    text: 'Thông tin loại đồ uống đã được cập nhật.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index_drinkcategories.php'; // Redirect về trang danh sách sau khi cập nhật
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
