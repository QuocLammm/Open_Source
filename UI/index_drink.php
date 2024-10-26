<?php
// Kết nối cơ sở dữ liệu
include("includes/connectSQL.php");
include("includes/Pager.php");

// Handle search/filter request
$searchDrink = $_GET['drink'] ?? '';
$searchCategory = $_GET['category'] ?? '';
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : "Giá nhỏ nhất"; // Sửa giá trị mặc định
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : "Giá cao nhất"; // Sửa giá trị mặc định

// Get drinks (search if applicable, otherwise get all)
$sql = "SELECT d.DrinkID, d.DrinkName, d.DrinkImage, d.DrinkPrice, dc.DrinkCategoryName
        FROM Drinks d
        JOIN Drinkcategories dc ON d.DrinkCategoryID = dc.DrinkCategoryID
        WHERE 1=1";

// Add search criteria
if ($searchDrink) {
    $sql .= " AND d.DrinkName LIKE '%" . $conn->real_escape_string($searchDrink) . "%'";
}
if ($searchCategory) {
    $sql .= " AND dc.DrinkCategoryName LIKE '%" . $conn->real_escape_string($searchCategory) . "%'";
}
if ($minPrice) {
    $sql .= " AND d.DrinkPrice >= " . (float)$minPrice;
}
if ($maxPrice < 99999999) {
    $sql .= " AND d.DrinkPrice <= " . (float)$maxPrice;
}

// Thực hiện truy vấn và lấy dữ liệu
$result = $conn->query($sql);
$drinks = $result->fetch_all(MYSQLI_ASSOC);

// Tạo đối tượng Pager với dữ liệu và số lượng sản phẩm mỗi trang
$pager = new Pager($drinks, 3); // 3 sản phẩm mỗi trang
$currentDrinks = $pager->getDataForCurrentPage(3); // Lấy dữ liệu cho trang hiện tại

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đồ uống</title>
    <link rel="stylesheet" href="path-to-your-css-file.css"> <!-- Add your CSS file for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <?php include("includes/_layoutAdmin.php"); ?>

    <div class="container mt-4">
        <form method="GET" class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Danh sách đồ uống</h3>
                <a href="create_drink.php" class="btn btn-success">Thêm</a>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="drink" class="form-label">Đồ uống:</label>
                        <input type="text" name="drink" id="drink" class="form-control" placeholder="Tên đồ uống" value="<?php echo htmlspecialchars($searchDrink); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Loại đồ uống:</label>
                        <input type="text" name="category" id="category" class="form-control" placeholder="Loại đồ uống" value="<?php echo htmlspecialchars($searchCategory); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="min_price" class="form-label">Giá:</label>
                        <input type="number" name="min_price" id="min_price" class="form-control" placeholder="Giá thấp nhất" value="<?php echo htmlspecialchars($minPrice); ?>">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="max_price" class="form-label">Đến:</label>
                        <input type="number" name="max_price" id="max_price" class="form-control" placeholder="Giá cao nhất" value="<?php echo htmlspecialchars($maxPrice); ?>">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary" onclick="window.location.reload();">Làm mới</button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên đồ uống</th>
                        <th>Loại đồ uống</th>
                        <th>Hình ảnh</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($currentDrinks)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Không tìm thấy dữ liệu</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($currentDrinks as $index => $drink): ?>
                            <tr>
                                <td><?php echo ($pager->getCurrentPage() - 1) * 3 + $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($drink['DrinkName']); ?></td>
                                <td><?php echo htmlspecialchars($drink['DrinkCategoryName']); ?></td>
                                <td><img src="images/drinks/<?php echo htmlspecialchars($drink['DrinkImage']); ?>" alt="Drink Image" width="50"></td>
                                <td><?php echo number_format($drink['DrinkPrice'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <a href="edit_drink.php?id=<?php echo $drink['DrinkID']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="<?php echo $drink['DrinkID']; ?>">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Hiển thị liên kết phân trang -->
            <div class="pagination justify-content-center mt-4">
                <?php echo $pager->getPaginationLinks(); ?>
            </div>

        </form>
    </div>

    <script>
        document.querySelectorAll('.btnDelete').forEach(button => {
            button.addEventListener('click', function() {
                const drinkId = this.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn xóa?')) {
                    window.location.href = `delete_drink.php?id=${drinkId}`;
                }
            });
        });
    </script>
</body>
</html>
