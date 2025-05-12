<!-- verify.php -->
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_otp = $_POST['otp'] ?? '';
    $pending = $_SESSION['pending_user'] ?? null;

    if (!$pending) {
        echo "Phiên đã hết hạn. Vui lòng đăng ký lại.";
        exit;
    }

    if ($input_otp == $pending['otp']) {
        // Mã OTP đúng, thêm người dùng vào CSDL
        require_once './database/db.php';
        $sql = "INSERT INTO users (masv, username, password, email, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $pending['masv'],
            $pending['username'],
            password_hash($pending['password'], PASSWORD_DEFAULT),
            $pending['email'],
            'sinhvien'
        ]);

        // Xóa thông tin người dùng tạm thời trong session
        unset($_SESSION['pending_user']);
        header("Location: index.php?success=1");
        exit;
    } else {
        echo "Mã xác thực không đúng. <a href='verify.php'>Thử lại</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác thực tài khoản</title>
</head>
<body>
  <h2>Nhập mã xác thực đã gửi qua email</h2>
  <form action="verify.php" method="POST">
    <input type="text" name="otp" placeholder="Nhập mã OTP" required>
    <button type="submit">Xác nhận</button>
  </form>
</body>
</html>
