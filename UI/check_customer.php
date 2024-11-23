<?php
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
include("includes/connectSQL.php");

// Lấy dữ liệu từ request
$inputData = json_decode(file_get_contents('php://input'), true);
$customerName = $inputData['name'] ?? '';

if ($customerName === '') {
    echo json_encode(['exists' => false, 'message' => 'Tên khách hàng không được để trống']);
    exit;
}

// Kiểm tra khách hàng
$stmt = $conn->prepare("SELECT CustomerID FROM customers WHERE CustomerName = ?");
$stmt->bind_param("s", $customerName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}
$stmt->close();
$conn->close();
exit;
