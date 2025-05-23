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
    WHERE loans.student_id = ? AND loans.status = 'returned'
    ORDER BY loans.return_date DESC
");
$stmt->execute([$user_id]);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách sách đã trả - Thư viện UniBooks</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <h1>📚 Danh sách sách đã trả: 📚</h1>

  <?php if (count($loans) > 0): ?>
    <ul>
      <?php foreach ($loans as $loan): ?>
        <li>
          <strong><?= htmlspecialchars($loan['title']) ?></strong> - Tác giả: <?= htmlspecialchars($loan['author']) ?>
          , Ngày mượn: <?= htmlspecialchars($loan['borrow_date']) ?>, Ngày trả: <?= htmlspecialchars($loan['return_date']) ?>
        </li><br>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Chưa có sách nào đã trả!</p>
  <?php endif; ?>

  <p><a href="../index.php">Quay lại trang chủ</a></p>
</body>
</html>
