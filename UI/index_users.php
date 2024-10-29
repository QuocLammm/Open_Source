<?php
include("includes/connectSQL.php");

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['delete_id'] ?? null;

    if ($id) {
        // Check if there are any associated UserCategories
        $checkSql = "SELECT COUNT(*) as count FROM UserCategories WHERE UserCategoryID IN (SELECT UserCategoryID FROM Users WHERE UserID = ?)";
        if ($checkStmt = $conn->prepare($checkSql)) {
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $row = $checkResult->fetch_assoc();

            // If associated UserCategories exist, prevent deletion
            if ($row['count'] > 0) {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa người dùng này vì có loại người dùng liên quan.']);
                exit;
            }
            $checkStmt->close();
        }

        // Prepare and execute the delete statement
        $sql = "DELETE FROM Users WHERE UserID = ?";
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




// Handle search request
$searchName = isset($_GET['name']) ? $_GET['name'] : '';
$searchRole = isset($_GET['role']) ? $_GET['role'] : '';

// Base SQL query
$sql = "SELECT u.UserID, u.FullName, uc.UserCategoryName, u.Gender, u.UserImage, u.PhoneNumber, u.Username, u.Password
        FROM Users u
        JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID
        WHERE 1=1"; // Add a placeholder to simplify appending conditions

// Prepare parameters for search
$params = [];
if (!empty($searchName)) {
    $sql .= " AND u.FullName LIKE ?";
    $params[] = '%' . $searchName . '%';
}
if (!empty($searchRole)) {
    $sql .= " AND uc.UserCategoryName LIKE ?";
    $params[] = '%' . $searchRole . '%';
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);

// Bind parameters if necessary
if (!empty($params)) {
    $types = str_repeat('s', count($params)); // All parameters are strings
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch all users as an associative array
$users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();
$conn->close();
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
                    <input type="text" name="name" class="form-control" placeholder="Tên người dùng" value="">
                </div>
                <div class="col">
                    <input type="text" name="role" class="form-control" placeholder="Tên loại người dùng" value="">
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
                    // Duyệt qua dữ liệu bằng foreach
                    if (!empty($users)) {
                        foreach ($users as $row) {
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
                    fetch('delete_users.php', { // Ensure this points to your delete logic
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json' // Changed to application/json
                        },
                        body: JSON.stringify({ delete_id: itemId }) // Changed to JSON format
                    })
                    .then(response => response.json()) // Parse the response as JSON
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Đã xóa thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload(); // Reload the page to update the list
                            });
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: data.message, // Use the message from the response
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

<?php
$conn->close();
?>
