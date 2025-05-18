<?php
session_start();
require_once '../database/db.php';

$masv = $_POST['masv'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';
$from_admin = isset($_POST['from_admin']) ? true : false;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_check = "SELECT * FROM users WHERE masv = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$masv]);
    $user_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($user_check) {
        $error = "Mã sinh viên này đã được đăng ký. Vui lòng kiểm tra lại.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (masv, username, password, email, role) 
                VALUES (:masv, :username, :password, :email, :role)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':masv', $masv);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $success = "Thêm người dùng thành công!";
            $masv = $username = $email = $password = '';
        } else {
            $error = "Lỗi thêm người dùng.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng</title>
    <link rel="stylesheet" href="../assets/users.css">
</head>
<body>
    <h2 style="text-align:center;">Thêm Người Dùng</h2>

    <div class="form-container">
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Mã sinh viên:</label>
            <input type="text" name="masv" value="<?= htmlspecialchars($masv) ?>" required>

            <label>Tên đăng nhập:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label>Mật khẩu:</label>
            <input type="password" name="password" required>

            <label>Vai trò:</label>
            <select name="role">
                <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <input type="hidden" name="from_admin" value="1">

            <div style="margin-top: 15px;">
                <input type="submit" value="Thêm người dùng">
                <a href="user_list.php" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
