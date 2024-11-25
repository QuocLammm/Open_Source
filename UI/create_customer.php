<?php
include("includes/session_user.php");

// Xử lý form khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $CustomerName = $_POST["CustomerName"];
    $Gender = $_POST["Gender"];
    $Address = $_POST["Address"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $Email = $_POST["Email"];
    $Offer = $_POST["Offer"];

    $sql = "INSERT INTO customer (CustomerName, Gender, Address, PhoneNumber, Email, Offer) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $CustomerName, $Gender, $Address, $PhoneNumber, $Email, $Offer);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Khách hàng đã được thêm thành công!</div>";
        // Thêm script để redirect sau khi hiển thị thông báo thành công
        echo "<script>
                alert('Khách hàng đã được thêm thành công!');
                window.location.href = 'index_customer.php';
              </script>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Khách Hàng Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1000px;
            margin-top: 20px;
        }
        .form-section {
            width: 100%;
            padding: 10px;
            margin: 70px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .form-control-1{
            display: block;
            width: 80%;
            height: 2.875rem;
            padding: 0.875rem 1.375rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1;
            color: #495057;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 2px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 300;
        }
        .row > div {
            padding-left: 5px;
            padding-right: 5px;
        }

        .container {
            max-width: 100%; /* Đảm bảo khung chính vừa đủ */
        }

        .form-control, .form-select {
            max-width: 100%; /* Đảm bảo không tràn cột */
            width: 30%; /* Đảm bảo tự điều chỉnh theo container */
        }

    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container">
        <form action="" method="POST" class="form-section">
            <h2 class="text-center">Thêm Khách Hàng Mới</h2>
            <a href="index_customer.php" class="btn btn-back mb-3">
                <i class="ti-arrow-left"></i> Quay lại
            </a>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="CustomerName" class="form-label">Tên khách hàng</label>
                        <input type="text" class="form-control-1 form-control-sm" id="CustomerName" name="CustomerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="Gender" class="form-label">Giới tính</label>
                        <select class="form-select form-control-sm" id="Gender" name="Gender" required>
                            <option value="Male">Nam</option>
                            <option value="Female">Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control-1 form-control-sm" id="Address" name="Address" rows="3" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="PhoneNumber" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control-1 form-control-sm" id="PhoneNumber" name="PhoneNumber" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control-1 form-control-sm" id="Email" name="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="Offer" class="form-label">Thành viên</label>
                        <div>
                            <input type="radio" id="vip" name="Offer" value="VIP" required>
                            <label for="vip">VIP</label>
                        </div>
                        <div>
                            <input type="radio" id="basic" name="Offer" value="Basic" required>
                            <label for="basic">Basic</label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="text-center">
                <button type="submit" class="btn btn-primary">Thêm</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
