<?php
include("include/connectSQL.php");

// Assuming you want to fetch all records from BillInfos
$query = "SELECT BillID, DrinkID, DrinkCount, DrinkPrice FROM BillInfos";
$result = $conn->query($query);

// Check if there are any results
if ($result && $result->num_rows > 0) {
    // Fetch all results as an associative array
    $billInfos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $billInfos = []; // No records found
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn</title>
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

        .invoice-box table tr td:nth-child(3) {
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
    <p style="font-size: 40px; font-weight: bold">HÓA ĐƠN</p>
    <p style="font-size: 20px">LÁ COFFEE</p>
    <p>Địa chỉ: 39 Hồng Bàng, Nha Trang, Khánh Hòa</p>
    <p>Số điện thoại: 0368410685</p>
    <p>Mã hóa đơn: <?php echo htmlspecialchars($billInfos[0]->BillID); ?></p>
    <p>Ngày: <?php echo date('Y-m-d H:i', strtotime($billInfos[0]->Bills->CreateDate)); ?></p>
    <p>Nhân viên: <?php echo htmlspecialchars($billInfos[0]->Bills->User->FullName); ?></p>
    <div class="invoice-box">
        <table>
            <tr class="heading">
                <td>#</td>
                <td>Tên đồ uống</td>
                <td>Số lượng</td>
                <td>Giá</td>
                <td>Thành tiền</td>
            </tr>
            <?php foreach ($billInfos as $item): ?>
                <tr class="item">
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($item->Drinks->DrinkName); ?></td>
                    <td><?php echo $item->DrinkCount; ?></td>
                    <td><?php echo number_format($item->Drinks->DrinkPrice, 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo number_format($item->DrinkPrice, 0, ',', '.'); ?> VNĐ</td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="4" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
                <td style="font-size: 25px; padding-top: 20px">
                    <?php 
                    $total = 0;
                    foreach ($billInfos as $item) {
                        $total += $item->DrinkCount * $item->Drinks->DrinkPrice;
                    }
                    echo number_format($total, 0, ',', '.'); ?> VNĐ
                </td>
            </tr>
        </table>
    </div>
    <p><i>Cảm ơn và hẹn gặp lại!</i></p>
</body>
</html>
