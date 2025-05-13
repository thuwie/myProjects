<?php
session_start();
require_once '../database/db.php'; // kết nối DB
$_SESSION['user_id'] = $user['masv'];
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
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['masv'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // Lưu role
    header("Location: ../index.php");
    exit;
} else {
    echo "<p style='color: red;'>Tên đăng nhập hoặc mật khẩu sai. Vui lòng thử lại.</p>";
}

?>