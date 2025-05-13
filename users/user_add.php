<?php
require_once '../database/db.php';
$masv = $_POST['masv'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = 'user';

$sql_check = "SELECT * FROM users WHERE username = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$username]);
$user_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

if($user_check){
     echo "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
}else{
    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Câu lệnh SQL để chèn dữ liệu vào bảng 'users'
    $sql = "INSERT INTO users (masv, username, password, email, role) VALUES (:masv, :username, :password, :email, :role)";

    // Chuẩn bị và thực thi câu lệnh
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':masv', $masv);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: ../index.php?success=1");
        exit;
    } else {
        echo "Lỗi thêm người dùng.";
    }
}
?>
