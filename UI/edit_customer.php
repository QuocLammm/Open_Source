<?php
// Kết nối cơ sở dữ liệu
include 'includes/session_user.php'; 

// Lấy CustomerID từ URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];

    // Truy vấn lấy thông tin khách hàng theo CustomerID
    $sql = "SELECT * FROM customer WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        echo "Khách hàng không tồn tại!";
        exit;
    }
} else {
    echo "Không có thông tin khách hàng để chỉnh sửa!";
    exit;
}

// Biến để hiển thị thông báo
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST["customerName"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $offer = isset($_POST["offer"]) ? $_POST["offer"] : 'Khách hàng thường';

    // Cập nhật dữ liệu khách hàng
    $sql = "UPDATE customer SET CustomerName = ?, Gender = ?, Address = ?, PhoneNumber = ?, Email = ?, offer = ? WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $customerName, $gender, $address, $phoneNumber, $email, $offer, $customerID);

    if ($stmt->execute()) {
        $successMessage = "Cập nhật khách hàng thành công!";
    } else {
        $successMessage = "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
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
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container mt-4">
        <form method="POST" class="form-section">
            <h3>Chỉnh Sửa Khách Hàng</h3>

            <div class="form-group">
                <label for="customerName">Tên khách hàng:</label>
                <input type="text" name="customerName" id="customerName" class="form-control" value="<?= $customer['CustomerName'] ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới tính:</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Nam" <?= ($customer['Gender'] == 'Nam') ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= ($customer['Gender'] == 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" name="address" id="address" class="form-control" value="<?= $customer['Address'] ?>" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại:</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?= $customer['PhoneNumber'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= $customer['Email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="offer">Loại thành viên:</label><br>
                <input type="radio" name="offer" value="Thành viên thường" id="offer_normal" <?= ($customer['offer'] == 'Thành viên thường') ? 'checked' : '' ?>>
                <label for="offer_normal">Thành viên thường</label><br>
                <input type="radio" name="offer" value="Thành viên VIP" id="offer_vip" <?= ($customer['offer'] == 'Thành viên VIP') ? 'checked' : '' ?>>
                <label for="offer_vip">Thành viên VIP</label>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật Khách Hàng</button>
        </form>
    </div>

    <!-- JavaScript để hiển thị thông báo -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($successMessage): ?>
                Swal.fire({
                    title: 'Cập nhật thành công!',
                    text: 'Thông tin khách hàng đã được cập nhật.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index_customer.php'; // Redirect về trang danh sách sau khi cập nhật
                });
            <?php endif; ?>
        });
    </script>

</body>
</html>
