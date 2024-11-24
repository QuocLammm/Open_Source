<?php
require_once("includes/session_user.php");

$userCategories = [];
$userCategoryName = '';

// Xử lý yêu cầu xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tìm kiếm theo tên loại người dùng
    $userCategoryName = $_POST['userCategoryName'] ?? '';
    $sql = "SELECT * FROM UserCategories WHERE UserCategoryName LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $userCategoryName . '%';
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $userCategories[] = $row;
    }

    $stmt->close();
} else {
    // Load tất cả loại người dùng khi trang tải lần đầu
    $sql = "SELECT * FROM UserCategories";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $userCategories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Loại Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
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
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-4">
        <form action="" method="POST" class="form-section">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Danh Sách Loại Người Dùng</h3>
                <a href="create_usercategories.php" class="btn btn-success">Thêm mới</a>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <input type="text" name="userCategoryName" class="form-control" placeholder="Tên loại người dùng" value="<?php echo htmlspecialchars($userCategoryName); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <a href="index_usercategories.php" class="btn btn-secondary ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
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
                            fetch('includes/delete_user_cate.php', { // Đảm bảo URL đúng với tệp xóa của bạn
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


    </script>
</body>
</html>

