<?php
include("includes/connectSQL.php");

// Handle delete request
if (isset($_POST['delete_id'])) {
    $deleteID = $_POST['delete_id'];

    // Prepare and execute delete query
    $deleteQuery = "DELETE FROM UserCategories WHERE UserCategoryID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $deleteID);

    if ($deleteStmt->execute()) {
        echo "<script>alert('Xóa thành công!'); window.location.href='index_usercategories.php';</script>";
    } else {
        // Catch foreign key constraint errors
        if (strpos($conn->error, 'foreign key constraint') !== false) {
            echo "<script>alert('Không thể xóa vì có ràng buộc dữ liệu với bảng khác!'); window.location.href='index_usercategories.php';</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    $deleteStmt->close();
}

// Define the SQL query to fetch user categories
$query = "SELECT UserCategoryID, UserCategoryName, UserCategoryDescription FROM UserCategories";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Loại Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <form action="" method="GET" class="form-section">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Danh Sách Loại Người Dùng</h3>
                <a href="create_usercategories.php" class="btn btn-success">Thêm mới</a>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Tên loại người dùng">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.reload();">Làm mới</button>
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><input type='checkbox' class='user-checkbox' data-id='{$row['UserCategoryID']}'></td>";
                            echo "<td>" . $row['UserCategoryID'] . "</td>";
                            echo "<td>" . $row['UserCategoryName'] . "</td>";
                            echo "<td>" . $row['UserCategoryDescription'] . "</td>";
                            echo "<td>
                                    <a href='edit_usercategories.php?id=" . $row['UserCategoryID'] . "' class='btn btn-sm btn-primary'>Sửa</a>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='delete_id' value='{$row['UserCategoryID']}'>
                                        <button type='submit' class='btn btn-outline-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\")'>Xóa</button>
                                    </form>
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
