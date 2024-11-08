<?php
require_once("includes/UsersCategoriesController.php");
require_once("includes/session_user.php");
//
$controller = new UserCategoriesController();
$userCategories = [];
$userCategoryName = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a delete request
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Assuming there's a method in the controller to handle deletion
        $deleteSuccess = $controller->delete($id);
        
        if ($deleteSuccess) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
        exit;
    }

    // Search functionality
    $userCategoryName = $_POST['userCategoryName'] ?? '';
    $userCategories = $controller->search($userCategoryName);
} else {
    // Load all user categories on initial page load
    $userCategories = $controller->index();
}




// Hiển thị dữ liệu
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Loại Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Thêm SweetAlert2 -->
</head>

<style>
    .container {
        max-width: 900px;
        margin-top: 20px;
    }

    .form-section {
        width: 102%;
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

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-4">
        <form action="" method="POST" class="form-section">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Danh Sách Loại Người Dùng</h3>
                <a href="create_usercategories.php" class="btn btn-success">Thêm mới</a>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <input type="text" name="userCategoryName" class="form-control" placeholder="Tên loại người dùng">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='index_usercategories.php';">Làm mới</button>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Tên loại người dùng</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($userCategories)) {
                        foreach ($userCategories as $row) {
                            echo "<tr>";
                            echo "<td><input type='checkbox' class='user-checkbox' data-id='{$row['UserCategoryID']}'></td>";
                            echo "<td>" . htmlspecialchars($row['UserCategoryID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['UserCategoryName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['UserCategoryDescription']) . "</td>";
                            echo "<td>
                                    <a href='edit_usercategories.php?id=" . $row['UserCategoryID'] . "' class='btn btn-sm btn-primary'>Sửa</a>
                                    <a href='#' class='btn btn-sm btn-danger btnDelete' data-id='" . $row['UserCategoryID'] . "'>Xóa</a>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        // Handle "select all" checkbox functionality
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Xóa bản ghi
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
                        body: JSON.stringify({ 
                            action: 'delete', 
                            id: itemId 
                        }),
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

    </script>
</body>
</html>

<?php
$conn->close();
?>