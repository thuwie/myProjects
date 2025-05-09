<?php
require_once('../db.php');
include("../includes/header.php");
include("../includes/nav.php");

$error_message = '';
$success_message = '';
$loan = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $loan_id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT l.*, b.title FROM loans l JOIN books b ON l.book_id = b.id WHERE l.id = ?");
        $stmt->execute([$loan_id]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            $error_message = "Không tìm thấy phiếu mượn.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi truy vấn: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id = $_POST['loan_id'] ?? '';
    $actual_return = $_POST['actual_return'] ?? date('Y-m-d');
    if (empty($loan_id)) {
        $error_message = "Thiếu thông tin phiếu mượn.";
    } else {
        try {
            $pdo->beginTransaction();
            // Lấy book_id để cập nhật trạng thái sách
            $stmt = $pdo->prepare("SELECT book_id FROM loans WHERE id = ?");
            $stmt->execute([$loan_id]);
            $book_id = $stmt->fetchColumn();
            // Cập nhật ngày trả thực tế
            $stmt = $pdo->prepare("UPDATE loans SET actual_return = ? WHERE id = ?");
            $stmt->execute([$actual_return, $loan_id]);
            // Cập nhật trạng thái sách
            $stmt = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
            $stmt->execute([$book_id]);
            $pdo->commit();
            $success_message = "Trả sách thành công!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error_message = "Lỗi khi trả sách: " . $e->getMessage();
        }
    }
}
?>

<main>
  <h2>Trả sách</h2>
  <?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
  <?php endif; ?>
  <?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
    <a href="loans_borrow.php" class="btn">Quay lại danh sách phiếu mượn</a>
  <?php elseif ($loan): ?>
    <form action="loans_return.php" method="POST" class="form-box">
      <input type="hidden" name="loan_id" value="<?= htmlspecialchars($loan['id']) ?>">
      <p><strong>Sách:</strong> <?= htmlspecialchars($loan['title']) ?></p>
      <p><strong>Ngày mượn:</strong> <?= htmlspecialchars($loan['borrow_date']) ?></p>
      <p><strong>Hạn trả:</strong> <?= htmlspecialchars($loan['return_date']) ?></p>
      <label for="actual_return">Ngày trả thực tế:</label>
      <input type="date" name="actual_return" id="actual_return" value="<?= date('Y-m-d') ?>" required>
      <input type="submit" value="Xác nhận trả sách" class="btn">
    </form>
  <?php endif; ?>
</main>
<?php include("../includes/footer.php"); ?>