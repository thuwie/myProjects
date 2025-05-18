<?php
session_start();
require_once '../database/db.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

if (!isset($_GET['masv'])) {
    header("Location: user_list.php");
    exit();
}

$masv = $_GET['masv'];
$error = '';
$success = '';

// Lấy thông tin user theo masv (role = user)
$sql = "SELECT * FROM users WHERE masv = ? AND role = 'user'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$masv]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: user_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email)) {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và email.";
    } else {
        $sql = "UPDATE users SET username = ?, email = ? WHERE masv = ?";
        $stmt = $pdo->prepare($sql);
        $success_update = $stmt->execute([$username, $email, $masv]);
        if ($success_update) {
            $success = "Cập nhật thành công!";
            // Cập nhật lại biến $user để hiển thị form
            $user['username'] = $username;
            $user['email'] = $email;
        } else {
            $error = "Cập nhật thất bại. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa Đọc Giả</title>
    <link rel="stylesheet" href="../assets/users.css">
</head>
<body>
    <h2 style="text-align:center;">Sửa thông tin Đọc Giả</h2>

    <div class="form-container">
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <div style="margin-top: 15px;">
                <input type="submit" value="Cập nhật">
                <a href="user_list.php" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
