<?php
// Include database connection file
include("includes/connectSQL.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['submit'])) {
    // Fetch form data
    $drinkCategoryName = mysqli_real_escape_string($conn, $_POST['drinkCategoryName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Check if required fields are not empty
    if (empty($drinkCategoryName)) {
        echo "Tên loại đồ uống không được để trống.";
    } else {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO DrinkCategories (DrinkCategoryName, DrinkCategoryDescription) VALUES (?, ?)");
        $stmt->bind_param("ss", $drinkCategoryName, $description);

        if ($stmt->execute()) {
            header("Location: index_drinkcategories.php?success=1");
            exit();
        } else {
            echo "Lỗi khi thêm loại đồ uống: " . $conn->error;
        }
        $stmt->close();
    }
}
// Close database connection
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
        .form-section {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
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
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm Loại Đồ Uống Mới</h3>
            <a href="index_drinkcategories.php" class="btn btn-primary mb-2">
                <i class="ti-arrow-left"></i> Quay lại
            </a>
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
            </div>
        </form>
    </div>

</body>
</html>
