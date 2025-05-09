<?php
session_start();
require_once '../database/db.php'; // kết nối DB

// Lấy dữ liệu từ form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Truy vấn người dùng từ DB
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra tồn tại & mật khẩu
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: ../index.php");
    exit;
} else {
    echo "Tên đăng nhập hoặc mật khẩu sai.";
}

?>