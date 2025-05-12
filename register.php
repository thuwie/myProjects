<!-- register.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký tài khoản</title>
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
  <form class="form" action="./users/user_add.php" method="POST">
    <div class="login">
      <div class="img-logo">
        <img src="./public/logo_login.png" alt="Book logo">
      </div>

      <div class="input-element">
        <input class="username" type="text" name="username" placeholder="Nhập tài khoản" required>

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
