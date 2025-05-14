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
  <title>Trang chủ - Quản lý thư viện</title>
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
      <li><a href="#"><i class="fas fa-home"></i> Trang chủ</a></li>
      <li class="history-toggle" style="cursor: pointer;">
        <i class="fas fa-history"></i> Xem lịch sử mượn sách
      </li>
      <ul class="history-submenu" style="display: none; padding-left: 20px;">
        <li><a href="#"><i class="fas fa-check"></i> Đã trả</a></li>
        <li><a href="#"><i class="fas fa-book"></i> Đang mượn</a></li>
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
      <li><a href="readers/readers_list.php">Quản Lý Đọc Giả</a></li>
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
      <table border="1" cellspacing="0" cellpadding="8" style="margin: auto;">
        <tr>
          <th>STT</th>
          <th>Hình ảnh</th>
          <th>Tiêu đề</th>
          <th>Tác giả</th>
          <th>Thể loại</th>
          <th>Tóm tắt</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
        <?php foreach ($books as $book): ?>
          <tr data-book-id="<?= $book['id'] ?>">
            <td><?php echo htmlspecialchars($book['id']); ?></td>
            <td>
              <?= !empty($book['images']) ? "<img src='" . htmlspecialchars($book['images']) . "' width='60'>" : "Không có" ?>
            </td>
            <td><?= htmlspecialchars($book['title']); ?></td>
            <td><?= htmlspecialchars($book['author']); ?></td>
            <td><?= htmlspecialchars($book['category']); ?></td>
            <td><?= htmlspecialchars($book['summary']) ?></td>
            <td><?= ($book['status'] === 'available') ? 'Sẵn sàng' : 'Đã mượn' ?></td>
            <td>
              <?php if ($book['status'] === 'available'): ?>
                <button 
                  onclick="openBorrowForm(this)"
                  data-id="<?= $book['id'] ?>"
                  data-title="<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>"
                  data-author="<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>"
                  data-category="<?= htmlspecialchars($book['category'], ENT_QUOTES) ?>"
                  data-summary="<?= htmlspecialchars($book['summary'], ENT_QUOTES) ?>"
                  data-status="<?= $book['status'] ?>"
                >Mượn</button>
              <?php else: ?>
                <button disabled style="opacity: 0.5;">Không khả dụng</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p>Không có sách nào trong hệ thống.</p>
    <?php endif; ?>

    <!-- FORM MƯỢN -->
    <div id="borrowForm" style="display:none; position:fixed; top:10%; left:30%; width:40%; background:#fff; padding:20px; border:1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index:9999;">
      <h3>Phiếu mượn sách</h3>
      <form method="POST" action="loans/loans_borrow.php">
        <input type="hidden" name="book_id" id="book_id">

        <p><strong>Tên sách:</strong> <span id="book_title"></span></p>
        <p><strong>Tác giả:</strong> <span id="book_author"></span></p>
        <p><strong>Thể loại:</strong> <span id="book_category"></span></p>
        <p><strong>Tóm tắt:</strong> <span id="book_summary"></span></p>
        <p><strong>Trạng thái:</strong> <span style="color: green;" id="book_status"></span></p>

        <p><strong>Mã sinh viên:</strong> <?= $_SESSION['user_id'] ?></p>
        <input type="hidden" name="student_id" value="<?= $_SESSION['user_id'] ?>">

        <label for="borrow_date">Ngày mượn:</label>
        <input type="date" name="borrow_date" id="borrow_date" required readonly><br>

        <label for="return_date">Ngày trả:</label>
        <input type="date" name="return_date" id="return_date" required><br><br>

        <button type="submit">Xác nhận mượn</button>
        <button type="button" onclick="closeBorrowForm()">Hủy</button>
      </form>
    </div>
      
    <!-- Lớp phủ mờ nền khi mở form -->
    <div id="formOverlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.5); z-index:9998;"></div>
    <script>
      function openBorrowForm(button) {
        // Lấy dữ liệu từ thuộc tính data-*
        const bookId = button.dataset.id;
        const title = button.dataset.title;
        const author = button.dataset.author;
        const category = button.dataset.category;
        const summary = button.dataset.summary;
        const status = button.dataset.status === 'available' ? 'Sẵn sàng' : 'Đã mượn';

        // Gán vào form
        document.getElementById('book_id').value = bookId;
        document.getElementById('book_title').innerText = title;
        document.getElementById('book_author').innerText = author;
        document.getElementById('book_category').innerText = category;
        document.getElementById('book_summary').innerText = summary;
        document.getElementById('book_status').innerText = status;

        // Hiện form
        document.getElementById('borrowForm').style.display = 'block';
        document.getElementById('formOverlay').style.display = 'block';
      }
      function closeBorrowForm() {
        document.getElementById('borrowForm').style.display = 'none';
        document.getElementById('formOverlay').style.display = 'none';
      }
      
      const borrowDateInput = document.getElementById('borrow_date');
      const returnDateInput = document.getElementById('return_date');
      // Gán ngày mượn mặc định là hôm nay
      const today = new Date().toISOString().split('T')[0];
      borrowDateInput.value = today;
      returnDateInput.min = today;

      // Khi người dùng chọn ngày mượn
      borrowDateInput.addEventListener('change', function () {
        returnDateInput.min = borrowDateInput.value; // Gán min ngày trả = ngày mượn
        // Nếu ngày trả hiện tại nhỏ hơn ngày mượn, reset ngày trả
        if (returnDateInput.value < borrowDateInput.value) {
          returnDateInput.value = '';
        }
      });

      // Khi người dùng submit form, kiểm tra logic
      document.querySelector('#borrowForm form').addEventListener('submit', function (e) {
        const borrowDate = new Date(borrowDateInput.value);
        const returnDate = new Date(returnDateInput.value);
      });
    </script>
  <?php else: ?>
    <p>Chào mừng đến với hệ thống quản lý thư viện. Vui lòng đăng nhập để sử dụng các chức năng.</p>
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