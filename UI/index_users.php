<?php
require_once("includes/session_user.php");

// Khởi tạo mảng để chứa người dùng
$users = [];
$fullName = $gender = $userCategoryName = '';

// Xử lý tìm kiếm người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $userCategoryName = $_POST['userCategoryName'] ?? '';
    
    // Truy vấn lấy người dùng theo điều kiện
    $sql = "SELECT u.UserID, u.FullName, u.Gender, u.UserCategoryID, u.UserImage, u.PhoneNumber, u.Username, u.Password, uc.UserCategoryName
            FROM Users u
            LEFT JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID
            WHERE 1=1";
    
    if ($fullName) {
        $sql .= " AND u.FullName LIKE ?";
        $params[] = '%' . $fullName . '%';
    }
    
    if ($gender) {
        $sql .= " AND u.Gender = ?";
        $params[] = $gender;
    }

    if ($userCategoryName) {
        $sql .= " AND uc.UserCategoryName LIKE ?";
        $params[] = '%' . $userCategoryName . '%';
    }

    $stmt = $conn->prepare($sql);

    // Gắn các tham số vào câu truy vấn
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $stmt->close();
} else {
    // Lấy tất cả người dùng
    $sql = "SELECT u.UserID, u.FullName, u.Gender, u.UserCategoryID, u.UserImage, u.PhoneNumber, u.Username, u.Password, uc.UserCategoryName
            FROM Users u
            LEFT JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
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
        width: 105%;
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
        text-decoration: none;
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
    <?php include('includes/_layoutAdmin.php'); ?>

    <div class="container mt-4">
        <form action="" method="POST" class="form-section">
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
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='index_users.php';">Load</button>
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
                            fetch('includes/delete_user.php', { // Đảm bảo URL đúng với tệp xóa của bạn
                                method: 'POST',
                                body: JSON.stringify({ delete_id: itemId }),  // Sửa từ 'id' thành 'delete_id'
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

            document.getElementById('select-all').addEventListener('change', function () {
                var checkboxes = document.querySelectorAll('.user-checkbox');
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = this.checked;
                });
            });
    </script>

</body>
</html>