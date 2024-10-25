<?php
// Establish the connection to the database
include("includes/connectSQL.php");

// Initialize variables
$drinkCategoryName = isset($_POST['drinkCategoryName']) ? $_POST['drinkCategoryName'] : '';
$drinkCategories = [];

// Define the SQL query to fetch drink categories
$query = "SELECT DrinkCategoryID, DrinkCategoryName, DrinkCategoryDescription FROM DrinkCategories";

// Add search condition if a search term is provided
if (!empty($drinkCategoryName)) {
    $query .= " WHERE DrinkCategoryName LIKE ?";
}

// Prepare and execute the query
if ($stmt = $conn->prepare($query)) {
    if (!empty($drinkCategoryName)) {
        $searchTerm = '%' . $drinkCategoryName . '%';
        $stmt->bind_param("s", $searchTerm);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $drinkCategories = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách loại đồ uống</title>
</head>
<style>
    .container {
            max-width: 900px;
            margin-top: 20px;
        }
    .form-section {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
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
                        <button type="button" class="btn btn-secondary" onclick="window.location.reload();">Làm mới</button>
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
                                <tr>
                                    <td><input type="checkbox" class="user-checkbox" data-id="<?= $item['DrinkCategoryID'] ?>"></td>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($item['DrinkCategoryName']) ?></td>
                                    <td><?= htmlspecialchars($item['DrinkCategoryDescription']) ?></td>
                                    <td>
                                        <a href="edit_drink_category.php?id=<?= $item['DrinkCategoryID'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $item['DrinkCategoryID'] ?>">
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function checkDeleteButtonVisibility() {
            var anyChecked = document.querySelectorAll('.cbkItem:checked').length > 0 || document.getElementById('SelectAll').checked;
            document.getElementById('btnDeleteAll').style.display = anyChecked ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            checkDeleteButtonVisibility();

            document.querySelectorAll('.cbkItem').forEach(function (checkbox) {
                checkbox.addEventListener('change', checkDeleteButtonVisibility);
            });

            document.getElementById('btnDeleteAll').addEventListener('click', function (e) {
                e.preventDefault();
                var checkedItems = Array.from(document.querySelectorAll('.cbkItem:checked')).map(cb => cb.value);
                if (checkedItems.length > 0) {
                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa các bản ghi này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('delete_drink_categories.php', {
                                method: 'POST',
                                body: JSON.stringify({ ids: checkedItems }),
                                headers: { 'Content-Type': 'application/json' }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });

            document.getElementById('SelectAll').addEventListener('change', function () {
                var checkStatus = this.checked;
                document.querySelectorAll('.cbkItem').forEach(function (checkbox) {
                    checkbox.checked = checkStatus;
                });
                checkDeleteButtonVisibility();
            });

            document.querySelectorAll('.btnDelete').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var itemId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('delete_drink_category.php', {
                                method: 'POST',
                                body: JSON.stringify({ id: itemId }),
                                headers: { 'Content-Type': 'application/json' }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
