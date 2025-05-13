<?php
session_start();
require_once '../database/db.php'; // kết nối DB
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role']; // thêm dòng này

// Lấy dữ liệu từ form
$masv = $_POST['masv'] ?? '';
$password = $_POST['password'] ?? '';

// Truy vấn người dùng từ DB
$sql = "SELECT * FROM users WHERE masv = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$masv]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra tồn tại & mật khẩu
echo $user

?>