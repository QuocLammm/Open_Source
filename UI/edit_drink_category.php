<?php
include("includes/session_user.php");
include("includes/DrinkCategoriesController.php"); // Bao gồm lớp điều khiển

// Lấy ID đồ uống từ URL
if (isset($_GET['id'])) {
    $drinkcateId = intval($_GET['id']);
    $controller = new DrinkCategoriesController($conn);

    // Truy vấn để lấy thông tin đồ uống
    $sql = "SELECT * FROM drinkcategories WHERE DrinkCategoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $drinkcateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy thông tin đồ uống
        $drinkcate = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy đồ uống.";
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
    $drinkName = isset($_POST['drinkName']) ? $_POST['drinkName'] : '';
    $drinkDescription = isset($_POST['drinkDescription']) ? $_POST['drinkDescription'] : '';

    // Cập nhật thông tin loại đồ uống
    if ($controller->update($drinkcateId, $drinkName, $drinkDescription)) {
        echo '<script>alert("Cập nhật loại đồ uống thành công.");</script>';
        echo "<script>window.location.href ='index_drinkcategories.php';</script>";
        exit();
    } else {
        echo '<script>alert("Có lỗi xảy ra: ' . $conn->error . '. Vui lòng thử lại.");</script>';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Đồ Uống</title>
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
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container">
        <form method="post" enctype="multipart/form-data" class="form-section">
            <h3 class="text-left mb-4">Chỉnh Sửa Loại Đồ Uống</h3>
            
            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="drinkName">Tên Đồ Uống</label>
                        <input type="text" id="drinkName" name="drinkName" value="<?php echo htmlspecialchars($drinkName); ?>" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="drinkDescription">Mô Tả</label>
                        <input type="text" id="drinkDescription" name="drinkDescription" value="<?php echo htmlspecialchars($drinkDescription); ?>" required autocomplete="off">
                    </div>                   
                </div>   
            </div>
            
            <div class="text-center btn-save">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="index_drinkcategories.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</body>
</html>