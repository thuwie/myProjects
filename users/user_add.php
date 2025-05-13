<?php
require_once '../database/db.php';
$masv = $_POST['masv'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql_check = "SELECT * FROM users WHERE masv = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$masv]);
$user_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

if($user_check){
     echo "Mã sinh viên này đã được đăng ký. Vui lòng kiểm tra lại.";
}else{
    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Câu lệnh SQL để chèn dữ liệu vào bảng 'users'
    $sql = "INSERT INTO users (masv, username, password, email) VALUES (:masv, :username, :password, :email)";

    // Chuẩn bị và thực thi câu lệnh
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':masv', $masv);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        header("Location: ../index.php?success=1");
        exit;
    } else {
        echo "Lỗi thêm người dùng.";
    }
}
?>
