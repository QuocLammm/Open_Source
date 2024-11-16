<?php
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
            $mail->Host = 'smtp.gmail.com'; // Thay đổi với SMTP server của bạn
            $mail->SMTPAuth = true;
            $mail->Username = 'linhdannguyen160707@gmail.com'; // Email người gửi
            $mail->Password = 'Quynhnhu@1607'; // Mật khẩu email người gửi
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;

            // Người gửi và người nhận
            $mail->setFrom('linhdannguyen160707@gmail.com', 'Coffe House L&T');
            $mail->addAddress('quoclam010305@gmail.com', 'Khách hàng'); // Địa chỉ email khách hàng

            // Nội dung email
            $mail->isHTML(true);
            $mail->Subject = 'Chúc mừng bạn đã nhận được voucher giảm giá!';
            $mail->Body    = ' Hello';

            // Gửi email
            $mail->send();
            echo 'Email đã được gửi thành công!';
        } catch (Exception $e) {
            echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
?>