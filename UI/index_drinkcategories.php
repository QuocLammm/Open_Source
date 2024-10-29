<?php
include("includes/connectSQL.php"); // Kết nối đến cơ sở dữ liệu
include("includes/DrinkCategoriesController.php"); // Bao gồm lớp điều khiển

// Khởi tạo điều khiển
$controller = new DrinkCategoriesController($conn);
// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if ($id) {
        // Prepare and execute the delete statement
        $sql = "DELETE FROM DrinkCategories WHERE DrinkCategoryID = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa bản ghi.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID không hợp lệ.']);
    }
    exit; // Stop further processing after handling the delete request
}

// Xử lý tìm kiếm nếu có
$drinkCategoryName = isset($_POST['drinkCategoryName']) ? $_POST['drinkCategoryName'] : '';
$drinkCategories = $controller->index();
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
        <form action="" method="GET" class="form-section"> <!-- Changed method to GET -->
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
                            <tr>
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
                btn.addEventListener('click', async function (e) {
                    e.preventDefault();
                    var itemId = this.getAttribute('data-id');
                    const result = await Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Hủy',
                    });
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch('', {
                                method: 'POST',
                                body: JSON.stringify({ id: itemId }),
                                headers: { 'Content-Type': 'application/json' }
                            });
                            const data = await response.json();
                            if (data.success) {
                                await Swal.fire({
                                    title: 'Đã xóa thành công!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                location.reload(); // Reload the page to update the list
                            } else {
                                await Swal.fire({
                                    title: 'Lỗi!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        } catch (error) {
                            await Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra. Vui lòng thử lại sau.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });

        function resetPage() {
            // Clear the search input
            document.querySelector('input[name="drinkCategoryName"]').value = '';
            
            // Reload the page without any query parameters
            window.location.href = window.location.pathname; // Redirect to the current page
        }

    </script>
</body>
</html>