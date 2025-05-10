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
    $stmt = $pdo->query("SELECT id, title FROM books WHERE status = 'available' ORDER BY title");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->query("SELECT student_id, name FROM readers ORDER BY name");
    $readers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi truy vấn: " . $e->getMessage();
}

// Xử lý mượn sách
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_submit'])) {
    $book_id = $_POST['book_id'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
    $return_date = $_POST['return_date'] ?? '';

    $errors = [];
    if (empty($book_id)) $errors[] = "Chưa chọn sách.";
    if (empty($student_id)) $errors[] = "Chưa chọn độc giả.";
    if (empty($borrow_date)) $errors[] = "Chưa nhập ngày mượn.";
    if (empty($return_date)) $errors[] = "Chưa nhập hạn trả.";

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO loans (book_id, student_id, borrow_date, return_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$book_id, $student_id, $borrow_date, $return_date]);
            $stmt = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?");
            $stmt->execute([$book_id]);
            $pdo->commit();
            $success_message = "Tạo phiếu mượn thành công!";
            // Làm mới danh sách sách
            $stmt = $pdo->query("SELECT id, title FROM books WHERE status = 'available' ORDER BY title");
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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
    $sql = "SELECT l.id, b.title, r.name AS reader_name, l.borrow_date, l.return_date, l.actual_return
            FROM loans l
            JOIN books b ON l.book_id = b.id
            JOIN readers r ON l.student_id = r.student_id
            ORDER BY l.borrow_date DESC";
    $stmt = $pdo->query($sql);
    $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi truy vấn: " . $e->getMessage();
}
?>

<main>
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

  <h2>Danh sách phiếu mượn</h2>
  <?php if (!empty($loans)): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên sách</th>
          <th>Độc giả</th>
          <th>Ngày mượn</th>
          <th>Hạn trả</th>
          <th>Ngày trả thực tế</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($loans as $loan): ?>
        <tr>
          <td><?= htmlspecialchars($loan['id']) ?></td>
          <td><?= htmlspecialchars($loan['title']) ?></td>
          <td><?= htmlspecialchars($loan['reader_name']) ?></td>
          <td><?= htmlspecialchars($loan['borrow_date']) ?></td>
          <td><?= htmlspecialchars($loan['return_date']) ?></td>
          <td><?= $loan['actual_return'] ? htmlspecialchars($loan['actual_return']) : '<span style="color:red">Chưa trả</span>' ?></td>
          <td>
            <?php if (!$loan['actual_return']): ?>
              <a href="loans_return.php?id=<?= $loan['id'] ?>" class="btn btn-small">Trả sách</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Chưa có phiếu mượn nào.</p>
  <?php endif; ?>
</main>
<?php include("../includes/footer.php"); ?>