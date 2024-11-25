<?php
require_once("connectSQL.php");

// Xử lý xóa báo cáo kết ca
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đọc dữ liệu JSON từ yêu cầu POST
    $data = json_decode(file_get_contents("php://input"), true);

    // Kiểm tra nếu có 'id' trong dữ liệu
    if (isset($data['id'])) {
        $deleteId = $data['id'];

        // Truy vấn xóa bản ghi ShiftReportID từ ShiftReports
        $stmt = $conn->prepare("DELETE FROM ShiftReports WHERE ShiftReportID = ?");
        $stmt->bind_param("s", $deleteId); // ID là chuỗi (vì ShiftReportID có dạng như KC24001)

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
