<?php
// Kết nối cơ sở dữ liệu
include 'includes/session_user.php'; // Giả sử file này chứa kết nối với biến $conn

// Kiểm tra xem có yêu cầu xóa không
if (isset($_POST['delete_id'])) {
    $customerID = $_POST['delete_id'];

    // Chuẩn bị câu lệnh SQL để xóa khách hàng
    $sql = "DELETE FROM customer WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Liên kết tham số
        $stmt->bind_param("i", $customerID);

        // Thực hiện câu lệnh SQL
        if ($stmt->execute()) {
            $messages = "Đã xóa thành công!";
        } else {
            $messages = "Lỗi khi xóa: " . $stmt->error;
        }

        // Đóng statement
        $stmt->close();
    } else {
        $messages = "Lỗi chuẩn bị câu lệnh SQL: " . $conn->error;
    }
}

// Truy vấn dữ liệu từ bảng customer
$sql = "SELECT * FROM customer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
    <style>
        .container {
        max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container mt-4">
        <form method="GET" class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Danh sách khách hàng</h3>
                <?php if (isset($messages)) echo "<p>$messages</p>"; ?>
                <a href="create_customer.php" class="btn btn-success">Thêm</a>
            </div> 
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th>#</th>
                        <th>Mã khách hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Thành viên</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $index = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $index++ . "</td>";
                        echo "<td>" . $row["CustomerID"] . "</td>";
                        echo "<td>" . $row["CustomerName"] . "</td>";
                        echo "<td>" . $row["Gender"] . "</td>";
                        echo "<td>" . $row["Address"] . "</td>";
                        echo "<td>" . $row["PhoneNumber"] . "</td>";
                        echo "<td>" . $row["Email"] . "</td>";
                        echo "<td>" . $row["offer"] . "</td>";
                        echo "<td>
                            <a href='edit_customer.php?id=" . $row['CustomerID'] . "' class='btn btn-sm btn-primary'>Sửa</a>
                            <form action='' method='POST' style='display:inline;'>
                                <input type='hidden' name='delete_id' value='" . $row['CustomerID'] . "'>
                                <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bản ghi này?\");'>Xóa</button>
                            </form>
                            <form action='send_mail.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='email_id' value='" . $row['CustomerID'] . "'>
                                <button type='submit' class='btn btn-sm btn-info'>Gửi Email</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Không có khách hàng nào.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>