<!-- register.php -->
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Nhận dữ liệu từ form
  $masv = $_POST['masv'] ?? '';
  $username = $_POST['username'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
    
  // Kiểm tra trùng tên đăng nhập hoặc email
  require_once './database/db.php';
  $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt_check = $pdo->prepare($sql_check);
  $stmt_check->execute([$username, $email]);
  $user_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

  if($user_check){
    echo "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký tài khoản</title>
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="register-body">
  <form class="form" action="./users/user_add.php" method="POST">
    <div class="login">
      <div class="img-logo">
        <img src="./public/logo_login.png" alt="Book logo">
      </div>

      <div class="input-element">
        <input class="masv" type="text" name="masv" placeholder="Nhập mã sinh viên" required>

        <input class="username" type="text" name="username" placeholder="Nhập tên sinh viên" required>

        <input class="email" type="email" name="email" placeholder="Nhập email" required>
        <span>Lưu ý: email sẽ được dùng để khôi phục tài khoản</span>

        <span class="password-section">
          <input class="password" type="password" name="password" placeholder="Nhập mật khẩu" required>
          <i class="fa-solid fa-eye toggle-password"></i>
        </span>
      </div>

      <div class="btn-login">
        <button type="submit">ĐĂNG KÝ</button>
      </div>

      <div class="register">
        <a href="index.php">Quay lại đăng nhập</a>
      </div>
    </div>
  </form>

  <script>
  // Bắt tất cả icon toggle-password
  document.querySelectorAll('.toggle-password').forEach(function(toggleIcon) {
    toggleIcon.addEventListener('click', function() {
      const passwordInput = this.previousElementSibling;
      const isPassword = passwordInput.type === "password";

      passwordInput.type = isPassword ? "text" : "password";
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  });
</script>
</body>
</html>
