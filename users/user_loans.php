<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT loans.id AS loan_id, loans.borrow_date, loans.return_date, books.title, books.author, books.images
    FROM loans
    JOIN books ON loans.book_id = books.id
    WHERE loans.student_id = ? AND loans.status = 'approved'
    ORDER BY loans.borrow_date DESC
");
$stmt->execute([$user_id]);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách sách đang mượn - Thư viện UniBooks</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <h1>📚 Danh sách sách đang mượn: 📚</h1>

  <?php if (count($loans) > 0): ?>
    <div class="book-list">
      <?php foreach ($loans as $loan): ?>
        <div class="book-card">
          <?php if (!empty($loan['images'])): ?>
            <img src="../books/<?= htmlspecialchars($loan['images']) ?>" alt="Ảnh bìa" class="book-image">
          <?php else: ?>
            <div class="book-placeholder">Không có ảnh</div>
          <?php endif; ?>
          <div class="book-title"><?= htmlspecialchars($loan['title']) ?></div>
          <div>Tác giả: <?= htmlspecialchars($loan['author']) ?></div>
          <div>Ngày mượn: <?= htmlspecialchars($loan['borrow_date']) ?></div>
          <div>Ngày trả dự kiến: <?= htmlspecialchars($loan['return_date']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Chưa có cuốn sách nào đang được mượn!</p>
  <?php endif; ?>

  <p><a href="../index.php">Quay lại trang chủ</a></p>
</body>
</html>
