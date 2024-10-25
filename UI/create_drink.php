<?php
// Include database connection
include("includes/connectSQL.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize an error variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drinkName = mysqli_real_escape_string($conn, $_POST['DrinkName']);
    $drinkCategoryID = mysqli_real_escape_string($conn, $_POST['DrinkCategoryID']);
    $drinkImage = $_FILES['DrinkImage']['name']; // Get the uploaded file name
    $drinkPrice = mysqli_real_escape_string($conn, $_POST['DrinkPrice']);

    // Check for required fields
    if (empty($drinkName) || empty($drinkCategoryID) || empty($drinkPrice)) {
        $error = "Vui lòng điền tất cả các trường bắt buộc.";
    } else {
        // Move uploaded image to desired folder
        move_uploaded_file($_FILES['DrinkImage']['tmp_name'], "images/drinks/" . $drinkImage);
        
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO Drinks (DrinkCategoryID, DrinkName, DrinkImage, DrinkPrice) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $drinkCategoryID, $drinkName, $drinkImage, $drinkPrice);

        if ($stmt->execute()) {
            header("Location: index_drink.php?success=1"); // Redirect on success
            exit();
        } else {
            $error = "Lỗi khi thêm đồ uống: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch drink categories for the dropdown
$categories = [];
$categoryQuery = $conn->query("SELECT DrinkCategoryID, DrinkCategoryName FROM drinkcategories");
if ($categoryQuery) {
    while ($row = $categoryQuery->fetch_assoc()) {
        $categories[] = $row;
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
    <title>Thêm Đồ Uống</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .form-section {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm Đồ Uống Mới</h3>
            <a href="index_drink.php" class="btn btn-primary mb-2">
                <i class="ti-arrow-left"></i> Quay lại
            </a>
            
            <div class="mb-3">
                <label for="DrinkName" class="form-label">Tên Đồ Uống <span class="text-danger">*</span></label>
                <input type="text" id="DrinkName" name="DrinkName" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="DrinkCategoryID" class="form-label">Loại Đồ Uống <span class="text-danger">*</span></label>
                <select id="DrinkCategoryID" name="DrinkCategoryID" class="form-control" required>
                    <option value="">Chọn loại đồ uống</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['DrinkCategoryID']; ?>"><?php echo $category['DrinkCategoryName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="DrinkImage" class="form-label">Hình Ảnh <span class="text-danger">*</span></label>
                <input type="file" id="DrinkImage" name="DrinkImage" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="DrinkPrice" class="form-label">Giá <span class="text-danger">*</span></label>
                <input type="number" id="DrinkPrice" name="DrinkPrice" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Lưu</button>
        </form>
    </div>
</body>
</html>