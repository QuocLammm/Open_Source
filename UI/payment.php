<?php
include("includes/connectSQL.php");

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

$tableID = $data['tableID'];
$items = $data['items'];
$totalAmount = $data['totalAmount'];

// Lấy CustomerID từ yêu cầu hoặc session (nếu giao diện gửi kèm CustomerID)
$customerID = $data['customerID'] ?? ($_SESSION['CustomerID'] ?? null);

// Lấy $userID từ cookies
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;

// Kiểm tra nếu CustomerID không tồn tại, thì thêm khách hàng mới (nếu cần thiết)
if (!$customerID && isset($data['customerName']) && $data['customerName'] !== "") {
    $customerName = $data['customerName'];
    $sqlInsertCustomer = "INSERT INTO customers (CustomerName) VALUES (?)";
    $stmtCustomer = $conn->prepare($sqlInsertCustomer);
    $stmtCustomer->bind_param("s", $customerName);
    $stmtCustomer->execute();

    // Lấy CustomerID vừa tạo
    $customerID = $conn->insert_id;
}

// Tạo một ID hóa đơn mới
$billID = "HD" . date('m') . date('d') . substr(uniqid(), -8);

// Lưu thông tin hóa đơn vào bảng bills
$sqlInsertBill = "INSERT INTO bills (BillID, CreateDate, TotalAmount, TableID, UserID, CustomerID) VALUES (?, NOW(), ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlInsertBill);
$stmt->bind_param("sdisi", $billID, $totalAmount, $tableID, $userID, $customerID);
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
