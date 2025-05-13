<?php
session_start();

// Chỉ cho phép người dùng đã đăng nhập và là sinh viên mới truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../index.php');
    exit;
}

require_once '../database/db.php';

// Truy vấn danh sách sách
$sql = "SELECT id, images, title, author, category, summary, status FROM books";
$stmt = $pdo->query($sql);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách sách hiện tại</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <header>
    <h1>Danh sách sách trong thư viện</h1>
  </header>

  <main>
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
            <?php if (!empty($book['images'])): ?>
              <img src="<?= htmlspecialchars($book['images']) ?>" alt="Ảnh bìa" width="60">
            <?php else: ?>
              Không có
            <?php endif; ?>
          </td>
          <td class="title"><?php echo htmlspecialchars($book['title']); ?></td>
          <td><?php echo htmlspecialchars($book['author']); ?></td>
          <td><?php echo htmlspecialchars($book['category']); ?></td>
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
    <div id="borrowForm" style="display:none; position:fixed; top:10%; left:30%; width:40%; background:#fff; padding:20px; border:1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index:1000;">
        <h3>Phiếu mượn sách</h3>
        <form method="POST" action="borrow_create.php">
            <input type="hidden" name="book_id" id="book_id">

            <p><strong>Tên sách:</strong> <span id="book_title"></span></p>
            <p><strong>Tác giả:</strong> <span id="book_author"></span></p>
            <p><strong>Thể loại:</strong> <span id="book_category"></span></p>
            <p><strong>Tóm tắt:</strong> <span id="book_summary"></span></p>
            <p><strong>Trạng thái:</strong> <span id="book_status"></span></p>

            <p><strong>Mã sinh viên:</strong> <?= $_SESSION['user_id'] ?></p>
            <input type="hidden" name="student_id" value="<?= $_SESSION['user_id'] ?>">

            <label for="borrow_date">Ngày mượn:</label>
            <input type="date" name="borrow_date" id="borrow_date" required><br>

            <label for="return_date">Ngày trả:</label>
            <input type="date" name="return_date" id="return_date" required><br><br>

            <button type="submit">Xác nhận mượn</button>
            <button type="button" onclick="closeBorrowForm()">Hủy</button>
        </form>
    </div>

    <!-- Lớp phủ mờ nền khi mở form -->
    <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.5); z-index:999;"></div>

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
          document.getElementById('overlay').style.display = 'block';
      }
      function closeBorrowForm() {
        document.getElementById('borrowForm').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      }
    </script>

    <script>
      const borrowDateInput = document.getElementById('borrow_date');
      const returnDateInput = document.getElementById('return_date');

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

        if (returnDate < borrowDate) {
          alert('Ngày trả không được trước ngày mượn!');
          e.preventDefault();
        }
      });
    </script>

    <div style="text-align: center; margin-top: 20px;">
      <a href="../index.php">Quay lại trang chủ</a>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Thư viện trường XYZ</p>
  </footer>
</body>
</html>
