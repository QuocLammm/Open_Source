<?php
include("includes/connectSQL.php");

// Lấy ID đồ uống từ URL
if (isset($_GET['id'])) {
    $drinkId = intval($_GET['id']);

    // Truy vấn để lấy thông tin đồ uống
    $sql = "SELECT d.*, c.DrinkCategoryName FROM drinks d 
            JOIN drinkcategories c ON d.DrinkCategoryID = c.DrinkCategoryID 
            WHERE d.DrinkID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $drinkId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy thông tin đồ uống
        $drink = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy đồ uống.";
        exit;
    }
} else {
    echo "ID không hợp lệ.";
    exit;
}

// Lấy tất cả loại đồ uống để hiển thị trong select
$sql_categories = "SELECT * FROM drinkcategories";
$result_categories = $conn->query($sql_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
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
            <h3 class="text-left mb-4">Chỉnh Sửa Đồ Uống</h3>
            
            <div class="form-row">
                <div class="form-column">
                    <div class="form-group">
                        <label for="drinkName">Tên Đồ Uống</label>
                        <input type="text" id="drinkName" name="drinkName" value="<?php echo htmlspecialchars($drink['DrinkName']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoryName">Tên Loại Đồ Uống</label>
                        <select id="categoryName" name="categoryName" required>
                            <option value="">Chọn loại đồ uống</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['DrinkCategoryID']; ?>" <?php echo ($category['DrinkCategoryID'] == $drink['DrinkCategoryID']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['DrinkCategoryName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-column">
                    <div class="form-group">
                        <label for="image">Hình Ảnh</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Giá Bán</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($drink['DrinkPrice']); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="text-center btn-save">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="index_drink.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</body>
</html>
