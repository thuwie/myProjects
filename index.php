<?php 
  session_start(); 
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
  <title>Thư viện UniBooks</title>
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="stylesheet" href="assets/more.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
  
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-message">
      Đăng ký thành công! Vui lòng đăng nhập.
    </div>
  <?php endif; ?>

  <form class="form" action="./middleware/middleware.php" method="POST" id ="login-form" style = "<?php echo $display?>">
      <div class="login">
          <div class="img-logo">
            <img src="./public/logo_login.png" alt="Book logo">
          </div>

          <div class="input-element">
            <input class="username" type="text" name="masv" alias="tài khoản" placeholder ="Nhập mã sinh viên" rules ="require">
            <span class="password-section">
              <input class="password" type="password" name="password" alias = "mật khẩu" placeholder ="Nhập mật khẩu" rules ="require|length-8">
              <i class="fa-solid fa-eye show-password"></i>
            </span>
            <span class="validation-message"></span>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Nhớ mật khẩu</label><br><br>
          </div>
          
          <div class="btn-login"> 
            <button class="btn-submit" type ="submit">ĐĂNG NHẬP</button>
          </div>
          
          <div class="register">
             <a href="./register.php">Đăng ký tài khoản</a>
          </div>
      </div>
  </form>

  <!-- Overlay nền mờ -->
  <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
  <!-- SIDEBAR (chỉ hiện nếu là user) -->
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    <div id="sidebar" class="sidebar">
      <button class="close-btn" onclick="toggleSidebar()">×</button>
      <ul class="sidebar-menu">
        <li class="history-toggle" style="cursor: pointer;">
          <i class="fas fa-history"></i> Xem lịch sử mượn sách
        </li>
        <ul class="history-submenu" style="display: none; padding-left: 20px;">
          <li><a href="users/user_returned.php"><i class="fas fa-check"></i> Đã trả</a></li>
          <li><a href="users/user_loans.php"><i class="fas fa-book"></i> Đang mượn</a></li>
        </ul>
        <li class="logout-btn" style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 15px;">
          <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </li>
      </ul>
    </div>
  <!-- Nút mở sidebar -->
  <button class="open-sidebar-btn" onclick="toggleSidebar()">☰</button>
  <?php endif; ?>

  <header>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
      <h1>📚 Danh sách sách hiện có:</h1>
    <?php else: ?>
      <h1>📖 Hệ thống quản lý thư viện 📖</h1>
    <?php endif; ?>
    <span class="user-avt">
      <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>
      <i class="fa-solid fa-user"></i>
    </span>
  </header>

  <nav>
    <ul>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <li><a href="books/books_list.php">Quản Lý Sách</a></li>
      <li><a href="users/user_list.php">Quản Lý Đọc Giả</a></li>
      <li><a href="loans/loans_borrow.php">Mượn / Trả Sách</a></li>
      <li><a href="logout.php">Đăng Xuất</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <main>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <p>Chào mừng <strong>Quản lý</strong> đến với hệ thống thư viện. Vui lòng chọn chức năng bên trên để tiếp tục.</p>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    
    <?php
    require_once 'database/db.php';
    $sql = "SELECT id, images, title, author, category, summary, status FROM books";
    $stmt = $pdo->query($sql);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (count($books) > 0): ?>
        <div class="book-list">
          <?php foreach ($books as $book): ?>
            <a href="./books/book_detail.php?id=<?= $book['id'] ?>" class="book-card">
              <?php if (!empty($book['images'])): ?>
                <img src="books/<?= htmlspecialchars($book['images']) ?>" 
                    alt="Ảnh bìa" 
                    class="book-image">
              <?php else: ?>
                <div class="book-placeholder">Không có ảnh</div>
              <?php endif; ?>
              <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
            </a>
          <?php endforeach; ?>
        </div>
    <?php else: ?>
      <p>Không có sách nào trong hệ thống.</p>
    <?php endif; ?>

  <?php else: ?>
    <p>Chào mừng đến với hệ thống thư viện. Vui lòng đăng nhập để sử dụng các chức năng.</p>
  <?php endif; ?>
</main>

<footer>
  <p>&copy; 2025 Thư viện UniBooks</p>
</footer>

<script src ="./validation/validator.js"> </script>
<script> validation('login-form'); </script>
<script>
  const historyToggle = document.querySelector('.history-toggle');
  const submenu = document.querySelector('.history-submenu');
  historyToggle?.addEventListener('click', () => {
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
  });
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
  }
</script>
</body>
</html>