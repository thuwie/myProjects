<?php
session_start();
require_once '../database/db.php'; // kết nối DB
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role']; // thêm dòng này

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
    $_SESSION['role'] = $user['role']; // Lưu role
    header("Location: ../index.php");
    exit;
} else {
    echo "<p style='color: red;'>Tên đăng nhập hoặc mật khẩu sai. Vui lòng thử lại.</p>";
}

?>