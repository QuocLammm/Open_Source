<?php
include("includes/connectSQL.php");

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

$tableID = $data['tableID'];
$items = $data['items'];
$totalAmount = $data['totalAmount'];
// Lấy $userID từ cookies
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;

// Tạo một ID hóa đơn mới
$billID = "HD" . date('m') . date('d') . substr(uniqid(), -8);

// Lưu thông tin hóa đơn vào bảng bills
$sqlInsertBill = "INSERT INTO bills (BillID, CreateDate, TotalAmount, TableID, UserID) VALUES (?, NOW(), ?, ?, ?)";
$stmt = $conn->prepare($sqlInsertBill);
$stmt->bind_param("sdis", $billID, $totalAmount, $tableID, $userID);
$stmt->execute();

// Lưu thông tin chi tiết hóa đơn vào bảng billinfos
foreach ($items as $item) {
    $drinkPrice = getDrinkPrice($item['drinkID'], $conn); // Hàm để lấy giá
    $sqlInsertBillInfo = "INSERT INTO billinfos (BillID, DrinkID, DrinkCount, DrinkPrice) VALUES (?, ?, ?, ?)";
    $stmtInfo = $conn->prepare($sqlInsertBillInfo);
    $totalDrinkPrice = $item['quantity'] * $drinkPrice;
    $stmtInfo->bind_param("siid", $billID, $item['drinkID'], $item['quantity'], $totalDrinkPrice);
    $stmtInfo->execute();
}

// Đóng kết nối
$stmt->close();
$conn->close();

// Trả về phản hồi
echo json_encode(['success' => true]);

// Hàm để lấy giá đồ uống
function getDrinkPrice($drinkID, $conn) {
    $sql = "SELECT DrinkPrice FROM drinks WHERE DrinkID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $drinkID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['DrinkPrice'];
}
?>