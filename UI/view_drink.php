<?php
include("includes/session_user.php");

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đồ uống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            width: 100%;
            padding: 20px;
            margin: 70px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .drink-image {
            max-width: 300px;
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        
        .row.align-items-center {
            display: flex;
            align-items: center;
        }
        .btn-primary.mb-2 {
            padding: 0.25rem 0.5rem; /* Adjust padding */
            font-size: 0.875rem; /* Smaller font size */
            width: 30%;
        }
        .a{
            width: 30%;
        }
    </style>
</head>
<body>
<?php include("includes/_layoutAdmin.php"); ?>
    <div class="container mt-4">
        <div class="form-section"> 
                <h2>Chi tiết đồ uống</h2>
            <div class="row align-items-center"> <!-- Added align-items-center -->
                <div class="col-md-3">
                    <img src="images/drinks/<?php echo htmlspecialchars($drink['DrinkImage']); ?>" alt="Drink Image" class="drink-image mb-3">
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Tên đồ uống: <?php echo htmlspecialchars($drink['DrinkName']); ?></h5>
                    <p><strong>Loại đồ uống:</strong> <?php echo htmlspecialchars($drink['DrinkCategoryName']); ?></p>
                    <p><strong>Giá:</strong> <?php echo number_format($drink['DrinkPrice'], 0, ',', '.'); ?>đ</p>
                </div>
                <a href="index_drink.php" class="btn btn-primary mb-2">
                    <i class="ti-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
            <script src="../UI/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
</body>
</html>