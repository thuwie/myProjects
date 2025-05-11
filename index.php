<?php 
  session_start(); // Khởi động session
  // Kiểm tra xem người dùng có đăng nhập không
  $display = "";
  if (isset($_SESSION['user_id'])) {
      $display = "display: none";
  } else {
    $display = "";
  };
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang chủ - Quản lý thư viện</title>
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

  <form class="form" action="./middleware/middleware.php" method="POST" id ="login-form" style = "<?php echo $display?>">
      <div class="login">
          <div class="img-logo">
            <img src="./public/logo_login.png" alt="Book logo">
          </div>

          <div class="input-element">
            <input class="username" type="text" name="username" alias="tài khoản" placeholder ="Nhập tài khoản" rules ="require">
            <span class="password-section">
              <input class="password" type="password" name="password" alias = "mật khẩu" placeholder ="Nhập mật khẩu" rules ="require|length-8">
              <i class="fa-solid fa-eye show-password"></i>
            </span>
            <span class="validation-message"></span>
          </div>
          
          <div class="btn-login"> 
            <button type ="submit">ĐĂNG NHẬP</button>
          </div>
          
          <div class="register">
             <a href="/">Đăng ký tài khoản</a>
          </div>
      </div>
  </form>

  <header>
    <h1>Hệ thống quản lý thư viện</h1>
     <span class="user-avt">
        <?php echo  $_SESSION['username']?>
        <i class="fa-solid fa-user"></i>
    </span>
  </header>

  <nav>
    <ul>
      <li><a href="books/books_list.php">Quản lý sách</a></li>
      <li><a href="readers/readers_list.php">Quản lý độc giả</a></li>
      <li><a href="loans/loans_borrow.php">Mượn / Trả sách</a></li>
    </ul>
  </nav>

  <main>
    <p>Chào mừng đến với hệ thống quản lý thư viện. Vui lòng chọn chức năng bên trên để tiếp tục.</p>
  </main>

  <footer>
    <p>&copy; 2025 Thư viện trường XYZ</p>
  </footer>
  <script src ="./validation/validator.js"> </script>
  <script> 
    validation('login-form');
  </script>
</body>
</html>
