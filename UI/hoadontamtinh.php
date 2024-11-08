<?php
include("includes/session_user.php");
// Handle table name from query string
$tableName = isset($_GET['tableName']) ? htmlspecialchars($_GET['tableName']) : ""; 
$tableID = null;

if ($tableName !== "") {
    $stmt = $conn->prepare("SELECT TableID FROM tables WHERE TableName = ?");
    $stmt->bind_param("s", $tableName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tableID = $row['TableID']; // Save tableID
    } else {
        echo "No tableID found for tableName: $tableName";
        exit;
    }
}
// Get bill information
$billID = $_GET['billID'] ?? 0;
$billQuery = "
    SELECT bi.DrinkCount, bi.DrinkPrice, d.DrinkName, b.CreateDate, t.TableName 
    FROM BillInfos bi
    INNER JOIN Drinks d ON bi.DrinkID = d.DrinkID
    INNER JOIN Bills b ON bi.BillID = b.BillID
    INNER JOIN Tables t ON b.TableID = t.TableID
    WHERE bi.BillID = ?";
$stmt = $conn->prepare($billQuery);
$stmt->bind_param("i", $billID);
$stmt->execute();
$billInfos = $stmt->get_result();

$total = 0;
$count = 1;
?>

<p style="font-size: 40px; font-weight: bold">HÓA ĐƠN TẠM TÍNH</p>
<p style="font-size: 20px">LÁ COFFEE</p>
<p>Địa chỉ: 39 Hồng Bàng, Nha Trang, Khánh Hòa</p>
<p>Số điện thoại: 0368410685</p>
<p>Ngày: <?= date("g", strtotime($billInfos->fetch_assoc()['CreateDate'])) ?></p>
<p>Số bàn: <?= $billInfos->fetch_assoc()['TableName'] ?></p>
<p>Nhân viên: <?= htmlspecialchars($user['FullName']) ?></p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
<div class="invoice-box">
    <table>
        <tr class="heading">
            <td>#</td>
            <td>Tên đồ uống</td>
            <td>Số lượng</td>
            <td>Giá</td>
            <td>Thành tiền</td>
        </tr>
        <?php while ($item = $billInfos->fetch_assoc()) : 
            $drinkTotal = $item['DrinkCount'] * $item['DrinkPrice'];
            $total += $drinkTotal;
        ?>
            <tr class="item">
                <td><?= $count++ ?></td>
                <td><?= htmlspecialchars($item['DrinkName']) ?></td>
                <td><?= $item['DrinkCount'] ?></td>
                <td><?= number_format($item['DrinkPrice'], 0, ',', '.') . "₫" ?></td>
                <td><?= number_format($drinkTotal, 0, ',', '.') . "₫" ?></td>
            </tr>
        <?php endwhile; ?>
        <tr class="total">
            <td colspan="4" style="font-size: 25px; padding-top: 20px; text-align: right;">Tổng cộng:</td>
            <td style="font-size: 25px; padding-top: 20px"><?= number_format($total, 0, ',', '.') . "₫" ?></td>
        </tr>
    </table>
</div>

<p><i>Cảm ơn và hẹn gặp lại!</i></p>
</body>
</html>



<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>
