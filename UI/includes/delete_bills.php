<?php
require_once("connectSQL.php");

// Kiểm tra nếu yêu cầu là POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ yêu cầu
    $data = json_decode(file_get_contents("php://input"), true);

    // Kiểm tra nếu ID hóa đơn tồn tại trong dữ liệu
    if (isset($data['id'])) {
        $billID = $data['id']; // BillID là chuỗi

        // Bắt đầu giao dịch (transaction) để đảm bảo tính toàn vẹn của dữ liệu
        $conn->begin_transaction();

        try {
            // Xóa các bản ghi trong bảng billinfos
            $sqlDeleteBillInfos = "DELETE FROM billinfos WHERE BillID = ?";
            $stmt = $conn->prepare($sqlDeleteBillInfos);
            $stmt->bind_param("s", $billID);
            $stmt->execute();

            // Kiểm tra nếu xóa billinfos thành công
            if ($stmt->affected_rows <= 0) {
                throw new Exception('Không tìm thấy bản ghi trong billinfos.');
            }

            // Xóa bản ghi trong bảng bills
            $sqlDeleteBill = "DELETE FROM bills WHERE BillID = ?";
            $stmt = $conn->prepare($sqlDeleteBill);
            $stmt->bind_param("s", $billID);
            $stmt->execute();

            // Kiểm tra nếu xóa bill thành công
            if ($stmt->affected_rows <= 0) {
                throw new Exception('Không thể xóa bản ghi trong bảng bills.');
            }

            // Commit giao dịch nếu tất cả các thao tác thành công
            $conn->commit();

            // Trả về kết quả thành công
            echo json_encode(['success' => true, 'message' => 'Hóa đơn và thông tin đã được xóa thành công!']);
        } catch (Exception $e) {
            // Nếu có lỗi xảy ra, rollback giao dịch
            $conn->rollback();

            // Trả về thông báo lỗi
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID hóa đơn không hợp lệ.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}

$conn->close();
?>
