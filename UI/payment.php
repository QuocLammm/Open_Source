<?php
require_once("includes/connectSQL.php");

$data = json_decode(file_get_contents("php://input"), true);
$tableID = $data['tableID'];
$items = $data['items'];
$totalAmount = $data['totalAmount'];

$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;

$billID = "HD" . date('ymd') . substr(uniqid(), -5);

try {
    $conn->begin_transaction();

    $sqlInsertBill = "INSERT INTO bills (BillID, CreateDate, TotalAmount, TableID, UserID) VALUES (?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsertBill);
    $stmt->bind_param("sdii", $billID, $totalAmount, $tableID, $userID);
    $stmt->execute();

    foreach ($items as $item) {
        $sqlInsertBillInfo = "INSERT INTO billinfos (BillID, DrinkID, DrinkCount) VALUES (?, ?, ?)";
        $stmtInfo = $conn->prepare($sqlInsertBillInfo);
        $stmtInfo->bind_param("sii", $billID, $item['drinkID'], $item['quantity']);
        $stmtInfo->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
