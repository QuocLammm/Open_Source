<?php
require_once("includes/session_user.php");

// Lấy thông tin từ POST
$data = json_decode(file_get_contents('php://input'), true);
$tableID = isset($data['tableID']) ? $data['tableID'] : null;

// Kiểm tra nếu tableID hợp lệ
if ($tableID) {
    // Cập nhật trạng thái bàn thành "Trống" (Status = 0)
    $stmt = $conn->prepare("UPDATE tables SET Status = 0 WHERE TableID = ?");
    $stmt->bind_param("i", $tableID);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]); // Trả về phản hồi thành công
    } else {
        // Ghi lỗi vào log để kiểm tra
        error_log("Error executing query: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái bàn']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Không có tableID hợp lệ']);
    exit;
}

$conn->close();
?>
