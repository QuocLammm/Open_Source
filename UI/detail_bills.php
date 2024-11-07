<?php
include("include/connectSQL.php");

// Assuming you have fetched $billInfos from the database
// For example: $billID = $_GET['id']; // Replace with your actual logic to get the bill ID
$billID = 1; // Replace with actual bill ID from the request
$query = "SELECT BillID, DrinkCount, DrinkPrice, Drinks.DrinkName, Bills.CreateDate, Users.FullName 
          FROM BillInfos 
          JOIN Bills ON BillInfos.BillID = Bills.BillID 
          JOIN Users ON Bills.UserID = Users.UserID 
          JOIN Drinks ON BillInfos.DrinkID = Drinks.DrinkID 
          WHERE BillInfos.BillID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $billID);
$stmt->execute();
$result = $stmt->get_result();
$billInfos = $result->fetch_all(MYSQLI_ASSOC);

$userFullName = ''; // Placeholder for user full name
if (!empty($billInfos)) {
    $userFullName = htmlspecialchars($billInfos[0]['FullName']);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết hóa đơn</title>
    <style>
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
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <p class="card-title">Chi tiết hóa đơn</p>
                <div>
                    <a class="btn btn-info mr-2" id="btnExportPDF" href="#">
                        <i class="ti-printer"></i>
                    </a>
                    <a href="index.php" class="btn btn-primary">
                        <i class="ti-arrow-left"></i>
                    </a>
                </div>
            </div>
            <p>Mã hóa đơn: <?php echo htmlspecialchars($billInfos[0]['BillID']); ?></p>
            <p>Ngày lập hóa đơn: <?php echo date('Y-m-d H:i', strtotime($billInfos[0]['CreateDate'])); ?></p>
            <p>Nhân viên: <?php echo $userFullName; ?></p>
            <div class="invoice-box">
                <table>
                    <tr class="heading">
                        <td>Tên đồ uống</td>
                        <td>Số lượng</td>
                        <td>Giá</td>
                        <td>Thành tiền</td>
                    </tr>
                    <?php 
                    $total = 0;
                    foreach ($billInfos as $item): 
                        $drinkPriceFormatted = number_format($item['DrinkPrice'], 0, ',', '.') . ' VNĐ';
                        $total += $item['DrinkCount'] * $item['DrinkPrice'];
                    ?>
                    <tr class="item">
                        <td><?php echo htmlspecialchars($item['DrinkName']); ?></td>
                        <td><?php echo $item['DrinkCount']; ?></td>
                        <td><?php echo $drinkPriceFormatted; ?></td>
                        <td><?php echo number_format($item['DrinkCount'] * $item['DrinkPrice'], 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
                        <td style="font-size: 25px; padding-top: 20px">
                            <?php echo number_format($total, 0, ',', '.') . ' VNĐ'; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
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
