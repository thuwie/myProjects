<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';

function sendmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'karanguyen6605@gmail.com';           // Thay bằng Gmail của bạn
        $mail->Password   = 'thuwiie.nth';             // Thay bằng App Password (KHÔNG dùng mật khẩu thật)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Gửi từ và tới
        $mail->setFrom('karanguyen6605@gmail.com', 'Hệ thống xác thực');
        $mail->addAddress($toEmail);  // Email người nhận

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = "$subject";
        $mail->Body    = nl2br($body); // Chuyển xuống dòng nếu có

        $mail->send();
        echo 'Email đã được gửi.';
    } catch (Exception $e) {
        echo "Gửi mail thất bại. Lỗi: {$mail->ErrorInfo}";
    }
}
