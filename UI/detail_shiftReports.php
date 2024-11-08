<?php
include("includes/session_user.php"); // Include session management
// Tạo một ID hóa đơn mới
$shiftReportID = "KC" . date('m') . date('d') . substr(uniqid(), -8);

// Assume you have the ShiftReportID passed via GET request
$shiftReportID = isset($_GET['id']) ? $_GET['id'] : null; 

// Initialize model variable
$model = null;

if ($shiftReportID) {
    // Fetch the shift report data from the database
    $query = "SELECT sr.ShiftReportID, u.FullName, sr.FirstTime, sr.LastTime, 
                     sr.BillCount, sr.UncollectedAmount, sr.Revenue, 
                     sr.FirstAmount, sr.LastAmount
              FROM ShiftReports sr 
              JOIN users u ON sr.UserID = u.UserID
              WHERE sr.ShiftReportID = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $shiftReportID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the report data
    $model = $result->fetch_assoc();
    
    // Close the statement
    $stmt->close();
}

// Check if model is null (report not found)
if (!$model) {
    echo "<p>Không tìm thấy báo cáo kết ca.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            max-width: 1000px;
            margin-top: 20px;
        }
        .form-section {
            width: 102%;
            padding: 10px;
            margin: 70px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container mt-4">
    <form action="" method="POST" class="form-section">
    <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <p class="card-title">Chi tiết báo cáo kết ca</p>
            <div>
                <a class="btn btn-info mr-2" id="btnExportPDF" href="#">
                    <i class="ti-printer"></i>
                </a>
                <a href="danhsach.php" class="btn btn-primary">
                    <i class="ti-arrow-left"></i>
                </a>
            </div>
        </div>
        <p style="text-align: center"><b>Mã báo cáo kết ca:</b> <?= htmlspecialchars($model['ShiftReportID']) ?></p>
        <p style="text-align: center"><b>Nhân viên:</b> <?= htmlspecialchars($model['FullName']) ?></p>
        <p style="text-align: center"><b>Giờ vào ca:</b> <?= htmlspecialchars(date("d/m/Y g:i A", strtotime($model['FirstTime']))) ?></p>
        <p style="text-align: center"><b>Giờ kết ca:</b> <?= htmlspecialchars(date("d/m/Y g:i A", strtotime($model['LastTime']))) ?></p>
        <p style="text-align: center"><b>Tổng số hóa đơn:</b> <?= htmlspecialchars($model['BillCount']) ?></p>
        <p style="text-align: center"><b>Đang phục vụ:</b> <?= htmlspecialchars(number_format($model['UncollectedAmount'], 0, ',', '.')) ?> VNĐ</p>
        <p style="text-align: center"><b>Doanh thu:</b> <?= htmlspecialchars(number_format($model['Revenue'], 0, ',', '.')) ?> VNĐ</p>
        <p style="text-align: center"><b>Số tiền đầu ca:</b> <?= htmlspecialchars(number_format($model['FirstAmount'], 0, ',', '.')) ?> VNĐ</p>
        <p style="text-align: center"><b>Số tiền kết ca:</b> <?= htmlspecialchars(number_format($model['LastAmount'], 0, ',', '.')) ?> VNĐ</p>
    </div>
</div>
    </form>
    </div>


<script>
$(document).ready(function () {
    $('#btnExportPDF').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/ShiftReports/ExportPDFCT',
            type: 'GET',
            success: function (rs) {
                location.reload();
            }
        });
    });
});
</script>
</body>
</html>