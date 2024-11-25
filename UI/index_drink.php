<?php
include("includes/Pager.php");
require_once("includes/session_user.php");
// Handle search/filter request
$searchDrink = isset($_GET['drinkName']) ? $_GET['drinkName'] : '';
$searchCategory = isset($_GET['category']) ? $_GET['category'] : '';
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
$pager = new Pager($drinks, 4); // 4 sản phẩm mỗi trang
$currentDrinks = $pager->getDataForCurrentPage(4); // Lấy dữ liệu cho trang hiện tại
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đồ uống</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            width: 100%;
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
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 5px;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination strong {
            color: red;
            border: 1px solid #007bff;
            padding: 8px 12px;
            border-radius: 5px;
        }

        /* Điều chỉnh chiều rộng của các ô tìm kiếm */
        #drinkName, #category {
            width: 100%;
            max-width: 220px;
        }

        /* Đảm bảo cả ô "Giá" và "Đến" nằm trên cùng một hàng */
        #min_price, #max_price {
            width: 100%;
            max-width: 120px;
            margin-right: 10px;
        }

        /* Các nút "Tìm kiếm" và "Tải lại" nằm cạnh nhau */
        .form-section .col-md-6 button {
            max-width: 130px;
        }

        /* Đảm bảo các ô input tìm kiếm và nút tìm kiếm đều sát nhau */
        .d-flex .col-md-6 {
            display: flex;
            gap: 10px;
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
                <div class="col-md-6 d-flex gap-3">
                    <div class="mb-3">
                        <label for="drink" class="form-label">Tên đồ uống:</label>
                        <input type="text" name="drinkName" id="drinkName" class="form-control" placeholder="Tên đồ uống" value="<?php echo htmlspecialchars($searchDrink); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Loại đồ uống:</label>
                        <input type="text" name="category" id="category" class="form-control" placeholder="Loại đồ uống" value="<?php echo htmlspecialchars($searchCategory); ?>">
                    </div>
                </div>
                
                <div class="col-md-6 d-flex gap-3">
                    <div class="mb-3">
                        <label for="min_price" class="form-label">Giá:</label>
                        <input type="number" name="min_price" id="min_price" class="form-control" placeholder="Giá thấp nhất" value="<?php echo htmlspecialchars($minPrice); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="max_price" class="form-label">Đến:</label>
                        <input type="number" name="max_price" id="max_price" class="form-control" placeholder="Giá cao nhất" value="<?php echo htmlspecialchars($maxPrice); ?>">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <a href="index_drink.php" class="btn btn-secondary ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
            </div>

            <table class="table table-bordered mt-4">
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
                                    <a href="view_drink.php?id=<?php echo $drink['DrinkID']; ?>" class="btn btn-sm btn-success">Xem</a>
                                    <a href="edit_drink.php?id=<?php echo $drink['DrinkID']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="<?= $drink['DrinkID'] ?>">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination justify-content-center mt-4">
                <?php echo $pager->getPaginationLinks(); ?>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btnDelete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var itemId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('includes/delete_drink.php', { // Đảm bảo URL đúng với tệp xóa của bạn
                        method: 'POST',
                        body: JSON.stringify({ id: itemId }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Xóa thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload(); // Tải lại trang để cập nhật danh sách
                            });
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Đã xảy ra lỗi khi xóa.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });
});
        function resetPage() {
            // Clear all search input fields
            document.querySelector('input[name="drinkName"]').value = '';
            document.querySelector('input[name="category"]').value = '';
            document.querySelector('input[name="min_price"]').value = '';
            document.querySelector('input[name="max_price"]').value = '';
            
            // Reload the page without any query parameters
            window.location.href = window.location.pathname;
        }
    </script>

</body>
</html>
