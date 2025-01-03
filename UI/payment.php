<?php
require_once("includes/connectSQL.php");

$data = json_decode(file_get_contents("php://input"), true);
$tableID = $data['tableID'];
$items = $data['items'];
$totalAmount = $data['totalAmount'];
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;
$customerID = $data['customerID'];  // Get the CustomerID from the request
$billID = "HD" . date('ymd') . substr(uniqid(), -5);

// Update the status of the table to "Occupied"
$stmt = $conn->prepare("UPDATE tables SET Status = 1 WHERE TableID = ?");
$stmt->bind_param("i", $tableID);
$stmt->execute();

try {
    $conn->begin_transaction();

    // Insert the bill, including the CustomerID
    $sqlInsertBill = "INSERT INTO bills (BillID, CreateDate, TotalAmount, TableID, UserID, CustomerID, Status) 
                      VALUES (?, NOW(), ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sqlInsertBill);
    $stmt->bind_param("sdiii", $billID, $totalAmount, $tableID, $userID, $customerID);
    $stmt->execute();

    // Insert the bill items along with DrinkPrice
    foreach ($items as $item) {
        // Get the price of the drink from the database
        $sqlGetPrice = "SELECT DrinkPrice FROM drinks WHERE DrinkID = ?";
        $stmtPrice = $conn->prepare($sqlGetPrice);
        $stmtPrice->bind_param("i", $item['drinkID']);
        $stmtPrice->execute();
        $stmtPrice->bind_result($drinkPrice);
        $stmtPrice->fetch();
        $stmtPrice->close();
    
        if ($drinkPrice === null) {
            // Nếu không có giá trị giá của món, bạn có thể xử lý lỗi hoặc thông báo
            echo json_encode(['success' => false, 'message' => "Không tìm thấy giá cho món với ID: " . $item['drinkID']]);
            exit;
        }
    
        // Insert the bill item with DrinkPrice
        $sqlInsertBillInfo = "INSERT INTO billinfos (BillID, DrinkID, DrinkCount, DrinkPrice) VALUES (?, ?, ?, ?)";
        $stmtInfo = $conn->prepare($sqlInsertBillInfo);
        $stmtInfo->bind_param("siii", $billID, $item['drinkID'], $item['quantity'], $drinkPrice);
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
