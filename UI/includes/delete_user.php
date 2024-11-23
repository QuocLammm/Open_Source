<?php
require_once("connectSQL.php");

// Xử lý xóa người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đọc dữ liệu JSON từ yêu cầu POST
    $data = json_decode(file_get_contents("php://input"), true);

    // Kiểm tra nếu có 'delete_id' trong dữ liệu
    if (isset($data['delete_id'])) {
        $deleteId = $data['delete_id'];

        // Truy vấn xóa bản ghi theo UserCategoryID
        $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $deleteId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa bản ghi']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Không có ID để xóa']);
    }
}
?>
