<?php
require_once("includes/session_user.php");

// Lấy thông tin từ POST
$data = json_decode(file_get_contents('php://input'), true);
$tableID = $data['tableID'];

// Kiểm tra nếu tableID hợp lệ
if ($tableID) {
    // Cập nhật trạng thái bàn thành "Trống" (Status = 0)
    $stmt = $conn->prepare("UPDATE tables SET Status = 0 WHERE TableID = ?");
    $stmt->bind_param("i", $tableID);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Error executing query: " . $stmt->error);  // Ghi lại lỗi vào log
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái bàn']);
    }
    
    $stmt->close();
} if (!isset($tableID) || empty($tableID)) {
    echo json_encode(['success' => false, 'message' => 'Không có tableID hợp lệ']);
    exit;
}


$conn->close();
?>
