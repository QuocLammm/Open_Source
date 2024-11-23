<?php
include("connectSQL.php");

// Kiểm tra yêu cầu POST và dữ liệu JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ POST JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && is_numeric($data['id'])) {
        $customerId = $data['id'];

        // Kiểm tra xem khách hàng có tồn tại không
        $sql = "SELECT * FROM customer WHERE CustomerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Tiến hành xóa khách hàng
            $deleteSql = "DELETE FROM customer WHERE CustomerID = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $customerId);

            if ($deleteStmt->execute()) {
                // Trả về phản hồi JSON thành công
                echo json_encode(['success' => true]);
            } else {
                // Trả về phản hồi JSON lỗi
                echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi xóa khách hàng.']);
            }
        } else {
            // Trả về phản hồi JSON lỗi nếu khách hàng không tồn tại
            echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại.']);
        }
    } else {
        // Trả về phản hồi JSON lỗi nếu không có id hợp lệ
        echo json_encode(['success' => false, 'message' => 'ID không hợp lệ.']);
    }
} else {
    // Trả về phản hồi JSON lỗi nếu không phải yêu cầu POST
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}

$conn->close();
?>
