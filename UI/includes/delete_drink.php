<?php
// Kết nối với cơ sở dữ liệu
include("connectSQL.php");

// Lấy ID từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if ($id) {
    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $sql = "DELETE FROM Drinks WHERE DrinkID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Xóa thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi trong quá trình xóa sản phẩm.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ.']);
}

$conn->close();
?>
