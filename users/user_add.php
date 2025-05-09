<?php
require_once '../database/db.php';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = 'user'; 

// Mã hóa mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Câu lệnh SQL để chèn dữ liệu vào bảng 'users'
$sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";

// Chuẩn bị và thực thi câu lệnh
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':role', $role);

if ($stmt->execute()) {
    header("Location: ../index.php");
    exit;
} else {
    echo "Error adding user.";
}
?>
