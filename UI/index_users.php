<?php
require_once("includes/UsersController.php");

$controller = new UsersController();
$users = [];
$fullName = $gender = $userCategoryName = '';
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['delete_id'])) {
    // Xóa một người dùng
    $controller->delete($data['delete_id']);
} elseif (isset($data['delete_ids'])) {
    // Xóa nhiều người dùng
    $ids = $data['delete_ids'];
    $controller->deleteAll($ids);
} else {
    echo json_encode(['success' => false, 'message' => 'Không có ID nào để xóa.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $userCategoryName = $_POST['userCategoryName'] ?? '';
    $users = $controller->search($fullName, $gender, $userCategoryName);
} else {
    $users = $controller->index();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .container {
        max-width: 800px;
        margin-top: 20px;
    }
    .form-section {
        width: 110%;
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
        <form action="" method="GET" enctype="multipart/form-data" class="form-section">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Danh Sách Người Dùng</h3>
            <a href="create_users.php" class="btn btn-success">Thêm mới</a>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="fullName" class="form-control" placeholder="Tên người dùng" value="<?php echo htmlspecialchars($fullName); ?>">
            </div>
            <div class="col">
                <input type="text" name="userCategoryName" class="form-control" placeholder="Tên loại người dùng" value="<?php echo htmlspecialchars($userCategoryName); ?>">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index_users.php';">Load lại</button>
            </div>
        </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Loại người dùng</th>
                        <th>Giới tính</th>
                        <th>Hình ảnh</th>
                        <th>Số điện thoại</th>
                        <th>Tên tài khoản</th>
                        <th>Mật khẩu</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    if (!empty($users)) {
        foreach ($users as $row) {
            // Kiểm tra UserID có tồn tại không
            if (isset($row['UserID'])) {
                echo "<tr>";
                echo "<td><input type='checkbox' class='user-checkbox' data-id='{$row['UserID']}'></td>";
                echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['FullName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserCategoryName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                echo "<td><img src='images/users/" . htmlspecialchars($row['UserImage']) . "' alt='image' style='width: 50px;'></td>";
                echo "<td>" . htmlspecialchars($row['PhoneNumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Password']) . "</td>";
                echo "<td>
                        <a href='edit_users.php?id=" . $row['UserID'] . "' class='btn btn-sm btn-primary'>Sửa</a>
                        <a href='#' class='btn btn-sm btn-danger btnDelete' data-id='" . $row['UserID'] . "'>Xóa</a>
                    </td>";
                echo "</tr>";
            } else {
                // Xử lý trường hợp không có UserID
                echo "<tr><td colspan='10' class='text-center'>Bản ghi không hợp lệ</td></tr>";
            }
        }
    } else {
        echo "<tr><td colspan='10' class='text-center'>Không có dữ liệu</td></tr>";
    }
    ?>
</tbody>
            </table>
        </form>
    </div>

    <script>
        // Confirm delete action
        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xóa không?");
        }

        // "Select all" checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        
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
                    // Send a POST request to delete the user
                    fetch('index_users.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ delete_id: itemId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Đã xóa thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
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

    // Thêm nút xóa nhiều người dùng
    document.getElementById('delete-selected').addEventListener('click', function () {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.getAttribute('data-id'))
            .join(',');

        if (selectedIds) {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa các bản ghi đã chọn?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_users.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ delete_ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Đã xóa thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
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
        } else {
            Swal.fire({
                title: 'Chưa chọn người dùng!',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    });
});

    </script>
</body>
</html>

<?php
$conn->close();
?>