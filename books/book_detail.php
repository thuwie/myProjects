<?php 
    require_once '../database/db.php';
    session_start();
    if (isset($_GET['id'])) {
        $book_id = $_GET['id']; 
         try {
            
                $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
                $stmt->execute([$book_id]);
                $book = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
    }
    };
?>
<?php include("../includes/header.php"); ?>

<main class ="main-css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/styles.css">

    <div class = "container-detail">
        <div class ="detail-box">
            <div class="book-image-wrapper">
                <?php if (!empty($book['images'])): ?>
                    <img src="<?= htmlspecialchars($book['images']) ?>" alt="Ảnh bìa" class="book-detail-image">
                <?php else: ?>
                    <div class="book-no-image">Không có ảnh</div>
                <?php endif; ?>
            </div>

            <div class="book-info">
                <h2 class="book-title"><?= htmlspecialchars($book['title']) ?></h2>
                <p><strong>Tác giả:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><strong>Thể loại:</strong> <?= htmlspecialchars($book['category']) ?></p>
                <p><strong>Năm xuất bản:</strong> <?= htmlspecialchars($book['publish_year']) ?></p>
                <p><strong>Tóm tắt:</strong> <?= nl2br(htmlspecialchars($book['summary'])) ?></p>
                <p><strong>Trạng thái:</strong><span style ="color: green">
                     <?= ($book['status'] === 'available') ? 'Sẵn sàng' : 'Đã mượn' ?>
                </span></p>

                <div class="btn-borrow-book">
                    <?php if ($book['status'] === 'available'): ?>
                        <button 
                        onclick="openBorrowForm(this)"
                        data-id="<?= $book['id'] ?>"
                        data-title="<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>"
                        data-author="<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>"
                        data-category="<?= htmlspecialchars($book['category'], ENT_QUOTES) ?>"
                        data-summary="<?= htmlspecialchars($book['summary'], ENT_QUOTES) ?>"
                        data-status="<?= $book['status'] ?>"
                        >Mượn sách</button>
                    <?php else: ?>
                        <button disabled style="opacity: 0.5;">Không khả dụng</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
      <!-- FORM MƯỢN -->
    <div id="borrowForm" style="display:none; position:fixed; top:10%; left:30%; width:40%; background:#fff; padding:20px; border:1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index:9999;">
      <h3>Phiếu mượn sách</h3>
      <form method="POST" action="../loans/loans_borrow.php">
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
</main>

<?php include("../includes/footer.php"); ?>
</body>
</html>
