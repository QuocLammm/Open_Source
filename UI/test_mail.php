<?php
// Kích hoạt phiên xử lý lỗi (dành cho phát triển)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Xử lý khi bấm nút "Gửi Email"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'E:\HK1_2025\OpenSource\bt_xampp\Open_Source\UI\PHPMailer-master\src\Exception.php';
    require 'E:\HK1_2025\OpenSource\bt_xampp\Open_Source\UI\PHPMailer-master\src\PHPMailer.php';
    require 'E:\HK1_2025\OpenSource\bt_xampp\Open_Source\UI\PHPMailer-master\src\SMTP.php';

    // Tạo đối tượng PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Cấu hình PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Máy chủ SMTP
        $mail->SMTPAuth = true; // Bật xác thực SMTP
        $mail->Username = 'quoclam010305@gmail.com'; // Địa chỉ email người gửi
        $mail->Password = 'Quynhnhu@1607'; // Mật khẩu email người gửi
        $mail->SMTPSecure = 'none'; // Không sử dụng bảo mật
        $mail->Port = 25; // Cổng SMTP không bảo mật

        // Người gửi và người nhận
        $mail->setFrom('quoclam010305@gmail.com', 'Coffe House L&T');
        $mail->addAddress('lam.cnq.63cntt@ntu.edu.com', 'Khách hàng');

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Chúc mừng bạn đã nhận được voucher giảm giá!';
        $mail->Body = '<b>Xin chào!</b> Bạn vừa nhận được voucher giảm giá từ Coffe House L&T.';

        // Gửi email
        $mail->send();
        echo '<p style="color: green;">Email đã được gửi thành công!</p>';
    } catch (Exception $e) {
        echo '<p style="color: red;">Không thể gửi email. Lỗi: ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Gửi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Test Gửi Email</h1>
    <form method="POST">
        <button type="submit" class="btn btn-primary">Gửi Email</button>
    </form>
</div>
</body>
</html>
