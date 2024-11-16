<?php
// Kết nối cơ sở dữ liệu
include 'includes/session_user.php'; // Giả sử file này chứa kết nối với biến $conn

// Biến để hiển thị thông báo
$successMessage = '';

// Xử lý khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST["customerName"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $offer = isset($_POST["offer"]) ? 'Thành viên' : 'Khách hàng thường';

    // Thêm dữ liệu vào bảng customer
    $sql = "INSERT INTO customer (CustomerName, Gender, Address, PhoneNumber, Email, offer) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $customerName, $gender, $address, $phoneNumber, $email, $offer);

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
    <title>Thêm Khách Hàng Mới</title>
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
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container mt-4">
        
        <form method="POST" class="form-section">
        <h3>Thêm Khách Hàng Mới</h3>
            <div class="form-group">
                <label for="customerName">Tên khách hàng:</label>
                <input type="text" name="customerName" id="customerName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới tính:</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại:</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="offer">Loại thành viên:</label><br>
                <input type="radio" name="offer" value="Thành viên thường" id="offer_normal">
                <label for="offer_normal">Thành viên Thường</label><br>
                <input type="radio" name="offer" value="Thành viên VIP" id="offer_vip">
                <label for="offer_vip">Thành viên VIP</label>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Khách Hàng</button>
        </form>
    </div>

    <!-- JavaScript để hiển thị thông báo -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($successMessage): ?>
                Swal.fire({
                    title: 'Thêm khách hàng thành công!',
                    text: 'Thông tin khách hàng đã được thêm.',
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

<?php
// Đóng kết nối
$conn->close();
?>
