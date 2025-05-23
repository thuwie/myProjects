<?php
require_once '../database/db.php';
include("../includes/header.php");
include("../includes/nav.php");

$error_message = '';
$success_message = '';

// Lấy danh sách sách còn available và độc giả
$books = [];
$readers = [];
try {
    $stmt = $pdo->query("SELECT id, title FROM books WHERE status = 'available' ORDER BY id");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->query("SELECT masv AS student_id, username AS name FROM users WHERE role = 'user' ORDER BY username");
    $readers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi truy vấn: " . $e->getMessage();
}

// Xử lý mượn sách
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $book_id = $_POST['book_id'] ?? '';
  $student_id = $_POST['student_id'] ?? '';
  $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
  $return_date = $_POST['return_date'] ?? '';
  $approved_at = $_POST['approved_at'] ?? '';
  $approved_by = $_POST['approved_by'] ?? '';

  $errors = [];
  if (empty($book_id)) $errors[] = "Chưa chọn sách.";
  if (empty($student_id)) $errors[] = "Chưa chọn độc giả.";
  if (empty($borrow_date)) $errors[] = "Chưa nhập ngày mượn.";
  if (empty($return_date)) $errors[] = "Chưa nhập hạn trả.";

  if (empty($errors)) {
    try {
      $pdo->beginTransaction();

      // Phân biệt form mượn từ xa hay mượn trực tiếp
      $status = isset($_POST['borrow_submit']) ? 'approved' : 'pending';
      $stmt = $pdo->prepare("INSERT INTO loans (book_id, student_id, borrow_date, return_date, status, approved_at, approved_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$book_id, $student_id, $borrow_date, $return_date, $status, $approved_at, $approved_by]);

      // Nếu từ form mượn tại thư viện
      if ($status === 'approved') {
        // Cập nhật trạng thái sách
        $stmt = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?");
        $stmt->execute([$book_id]);
        $pdo->commit(); // commit sau cả insert và update

        //Load lại danh sách sách 'available' sau khi mượn
        $stmt = $pdo->query("SELECT id, title FROM books WHERE status = 'available' ORDER BY id");
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $success_message = "Tạo phiếu mượn thành công (đã duyệt ngay).";
        
      // Nếu từ form mượn từ xa
      } else {
        $stmt = $pdo->prepare("UPDATE books SET status = 'pending' WHERE id = ?");
        $stmt->execute([$book_id]);
        $pdo->commit(); // chỉ cần commit insert
        
        echo "<script>
          alert('✅ Đã xác nhận mượn sách, hãy đến thư viện để lấy sách!');
          window.location.href = '../index.php';
        </script>";
        exit;
      }
    } catch (Exception $e) {
      $pdo->rollBack();
      $error_message = "Lỗi khi mượn sách: " . $e->getMessage();
    }
  } else {
    $error_message = implode("<br>", $errors);
  }
}

// Lấy danh sách phiếu mượn
$loans = [];
try {
    $sql = "SELECT l.id, b.title, u.username AS reader_name, l.borrow_date, l.return_date, l.status
        FROM loans l
        JOIN books b ON l.book_id = b.id
        JOIN users u ON l.student_id = u.masv
        WHERE l.status IN ('pending', 'approved')
        ORDER BY l.borrow_date DESC";
    $stmt = $pdo->query($sql);
    $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi truy vấn: " . $e->getMessage();
}
?>

<main>
  <style>
    table {
    border-collapse: collapse;
    width: 100%;
  }

  table, th, td {
    border: 1px solid #333;
  }

  th, td {
    padding: 8px;
    text-align: left;
  }
  </style>
  <h2>Mượn sách</h2>
  <?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
  <?php endif; ?>
  <?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
  <?php endif; ?>

  <form action="loans_borrow.php" method="POST" class="form-box">
    <label for="book_id">Chọn sách:</label>
    <select name="book_id" id="book_id" required>
      <option value="">-- Chọn sách --</option>
      <?php foreach ($books as $book): ?>
        <option value="<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="student_id">Chọn độc giả:</label>
    <select name="student_id" id="student_id" required>
      <option value="">-- Chọn độc giả --</option>
      <?php foreach ($readers as $reader): ?>
        <option value="<?= htmlspecialchars($reader['student_id']) ?>"><?= htmlspecialchars($reader['name']) ?> (<?= htmlspecialchars($reader['student_id']) ?>)</option>
      <?php endforeach; ?>
    </select>

    <label for="borrow_date">Ngày mượn:</label>
    <input type="date" name="borrow_date" id="borrow_date" value="<?= date('Y-m-d') ?>" required>

    <label for="return_date">Hạn trả:</label>
    <input type="date" name="return_date" id="return_date" required>

    <input type="submit" name="borrow_submit" value="Lưu phiếu mượn" class="btn">
  </form>

  <h2>Danh Sách Phiếu Mượn:</h2>
  <?php if (!empty($loans)): ?>
    <table>
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên sách</th>
          <th>Đọc giả</th>
          <th>Ngày mượn</th>
          <th>Hạn trả</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php $stt = 1; ?>
        <?php foreach ($loans as $loan): ?>
        <tr>
          <td><?= $stt ?></td>

          <td><?= htmlspecialchars($loan['title']) ?></td>
          <td><?= htmlspecialchars($loan['reader_name']) ?></td>
          <td><?= htmlspecialchars($loan['borrow_date']) ?></td>
          <td><?= htmlspecialchars($loan['return_date']) ?></td>
          <td>
            <?php if ($loan['status'] === 'pending'): ?>
              <a href="loans_approve.php?id=<?= htmlspecialchars($loan['id']) ?>" onclick="return confirm('Bạn có muốn duyệt phiếu này không?')">Duyệt</a>
            <?php elseif ($loan['status'] === 'approved'): ?>
              <a href="loans_return.php?id=<?= htmlspecialchars($loan['id']) ?>" onclick="return confirm('Bạn có chắc muốn trả sách này không?')">Trả sách</a>
            <?php else: ?>
              <span style="color:gray">Đã trả</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php $stt++; endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Không có phiếu mượn nào đang chờ duyệt.</p>
  <?php endif; ?>
</main>
<?php include("../includes/footer.php"); ?>