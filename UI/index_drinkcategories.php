<?php
require_once("includes/session_user.php");

// Lấy giá trị drinkCategoryName từ query string nếu có
$drinkCategoryName = isset($_GET['drinkCategoryName']) ? $_GET['drinkCategoryName'] : '';

// Tạo truy vấn SQL để tìm kiếm các loại đồ uống
$query = "SELECT * FROM DrinkCategories WHERE DrinkCategoryName LIKE ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error preparing query: ' . $conn->error);
}

$searchTerm = "%" . $drinkCategoryName . "%";
$stmt->bind_param('s', $searchTerm); 
$stmt->execute();

if ($stmt->error) {
    die('Error executing query: ' . $stmt->error);
}

$result = $stmt->get_result();
$drinkCategories = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách loại đồ uống</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

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
</style>

<body>
    <?php include("includes/_layoutAdmin.php"); ?>

    <div class="container mt-4">
        <form action="" method="GET" class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Danh Sách Loại Đồ Uống</h3>
                <a href="create_drink_category.php" class="btn btn-success">Thêm mới</a>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <input class="form-control" type="text" name="drinkCategoryName" placeholder="Tên loại đồ uống" value="<?= htmlspecialchars($drinkCategoryName) ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <button type="button" class="btn btn-secondary" onclick="resetPage();">Làm mới</button>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="SelectAll"></th>
                        <th>#</th>
                        <th>Tên loại đồ uống</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($drinkCategories)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($drinkCategories as $index => $item): ?>
                            <tr data-id="<?= $item['DrinkCategoryID'] ?>">
                                <td><input type="checkbox" class="user-checkbox" data-id="<?= $item['DrinkCategoryID'] ?>"></td>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($item['DrinkCategoryName']) ?></td>
                                <td><?= htmlspecialchars($item['DrinkCategoryDescription']) ?></td>
                                <td>
                                    <a href="edit_drink_category.php?id=<?= $item['DrinkCategoryID'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="<?= $item['DrinkCategoryID'] ?>">Xóa</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
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
                    fetch('includes/delete_drink_cate.php', { // Đảm bảo URL đúng với tệp xóa của bạn
                        method: 'POST',
                        body: JSON.stringify({ ids: itemId }),
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
            document.querySelector('input[name="drinkCategoryName"]').value = '';
            window.location.href = window.location.pathname;
        }
    </script>

</body>
</html>
