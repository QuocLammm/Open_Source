<?php
include("includes/connectSQL.php");

// Xử lý yêu cầu xóa người dùng
if (isset($_POST['delete_id'])) {
    $deleteID = $_POST['delete_id'];

    // Debugging để kiểm tra giá trị delete_id
    echo "Delete ID: " . htmlspecialchars($deleteID);

    // Chuẩn bị và thực hiện truy vấn xóa
    $deleteQuery = "DELETE FROM usercategories WHERE UserCategoryID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $deleteID);

    if ($deleteStmt->execute()) {
        echo "<script>alert('Xóa thành công!'); window.location.href='index_users.php';</script>";
    } else {
        // Bắt lỗi ràng buộc khóa ngoại
        if (strpos($conn->error, 'foreign key constraint') !== false) {
            echo "<script>alert('Không thể xóa người dùng vì có ràng buộc dữ liệu với bảng khác!'); window.location.href='index_users.php';</script>";
        } else {
            error_log("Error deleting record: " . $conn->error); // Ghi lại lỗi
            echo "Error deleting record: " . $conn->error;
        }
    }

    $deleteStmt->close();
}

// Chuẩn bị truy vấn SQL dựa trên thông tin tìm kiếm
$name = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';
$role = isset($_GET['role']) ? $conn->real_escape_string($_GET['role']) : '';

$sql = "SELECT u.UserID, u.FullName, uc.UserCategoryName, u.Gender, u.UserImage, u.PhoneNumber, u.Username, u.Password
        FROM users u
        JOIN usercategories uc ON u.UserCategoryID = uc.UserCategoryID
        WHERE u.FullName LIKE '%$name%' AND uc.UserCategoryName LIKE '%$role%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        overflow-x: hidden;
    }
    .container {
        max-width: 1200px;
        margin-top: 20px;
    }
    .form-section {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        width: 120%;
        margin: 120px 0 80px;
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
                    <input type="text" name="name" class="form-control" placeholder="Tên người dùng" value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="col">
                    <input type="text" name="role" class="form-control" placeholder="Tên loại người dùng" value="<?php echo htmlspecialchars($role); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.reload();">Load</button>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Tên loại người dùng</th>
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><input type='checkbox' class='user-checkbox' data-id='{$row['UserID']}'></td>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['FullName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['UserCategoryName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                            echo "<td><img src='../UI/images/users/" . htmlspecialchars($row['UserImage']) . "' alt='image' style='width: 50px;'></td>";
                            echo "<td>" . htmlspecialchars($row['PhoneNumber']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Password']) . "</td>";
                            echo "<td>
                                    <a href='edit_users.php?id=" . $row['UserID'] . "' class='btn btn-sm btn-primary'>Sửa</a>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='delete_id' value='{$row['UserID']}'>
                                        <button type='submit' class='btn btn-outline-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\")'>Xóa</button>
                                    </form>
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
        // JavaScript to handle the "select all" checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
