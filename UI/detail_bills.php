<?php
require_once("includes/session_user.php");

// Kiểm tra xem BillID có tồn tại trong URL không
if (isset($_GET['BillID'])) {
    $billID = urldecode($_GET['BillID']); // Lấy BillID từ URL
} else {
    echo "Mã hóa đơn không hợp lệ.";
    exit();
}

// Truy vấn thông tin hóa đơn từ cơ sở dữ liệu
$query = "SELECT BillInfos.BillID, BillInfos.DrinkCount, BillInfos.DrinkPrice, Drinks.DrinkName, Bills.CreateDate, Users.FullName 
          FROM BillInfos 
          JOIN Bills ON BillInfos.BillID = Bills.BillID 
          JOIN Users ON Bills.UserID = Users.UserID 
          JOIN Drinks ON BillInfos.DrinkID = Drinks.DrinkID 
          WHERE BillInfos.BillID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $billID); // Sử dụng 's' vì BillID có thể là chuỗi
$stmt->execute();
$result = $stmt->get_result();
$billInfos = $result->fetch_all(MYSQLI_ASSOC);

if (empty($billInfos)) {
    echo "Không tìm thấy dữ liệu cho hóa đơn này.";
    exit();
}

// Lấy thông tin người dùng
$userFullName = htmlspecialchars($billInfos[0]['FullName']);

// Tính tổng tiền
$total = 0;
foreach ($billInfos as $item) {
    $total += $item['DrinkCount'] * $item['DrinkPrice'];
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết hóa đơn</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Đảm bảo đường dẫn đúng -->
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
        h1, h2, p {
            text-align: center;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    
    <div class="container mt-4">
        <form action="" method="POST" class="form-section">
        <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <p class="card-title">Chi tiết hóa đơn</p>
                <div>
                    <a class="btn btn-info mr-2" id="btnExportPDF" href="#">
                        <i class="ti-printer"></i>
                    </a>
                    <a href="index_bills.php" class="btn btn-primary">
                        <i class="ti-arrow-left"></i>
                    </a>
                </div>
            </div>
            <p>Mã hóa đơn: <?= htmlspecialchars($billInfos[0]['BillID']); ?></p>
            <p>Ngày lập hóa đơn: <?= date('Y-m-d H:i', strtotime($billInfos[0]['CreateDate'])); ?></p>
            <p>Nhân viên: <?= $userFullName; ?></p>
            <div class="invoice-box">
                <table>
                    <tr class="heading">
                        <td>Tên đồ uống</td>
                        <td>Số lượng</td>
                        <td>Giá</td>
                        <td>Thành tiền</td>
                    </tr>
                    <?php foreach ($billInfos as $item): ?>
                        <tr class="item">
                            <td><?= htmlspecialchars($item['DrinkName']); ?></td>
                            <td><?= $item['DrinkCount']; ?></td>
                            <td><?= number_format($item['DrinkPrice'], 0, ',', '.') . ' VNĐ'; ?></td>
                            <td><?= number_format($item['DrinkCount'] * $item['DrinkPrice'], 0, ',', '.') . ' VNĐ'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
                        <td style="font-size: 25px; padding-top: 20px">
                            <?= number_format($total, 0, ',', '.') . ' VNĐ'; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
        </form>
    </div>
    
    <script>
        document.getElementById('btnExportPDF').addEventListener('click', function (e) {
            e.preventDefault();
            // Implement the logic to export PDF
            alert('Export to PDF functionality to be implemented.');
        });
    </script>
</body>
</html>