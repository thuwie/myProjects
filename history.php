<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Chuyển hướng về trang chủ nếu chưa đăng nhập
    exit;
}

require_once 'database/db.php';

$student_id = $_SESSION['user_id'];

$sql = "SELECT 
          b.title, 
          b.author, 
          br.borrow_date, 
          br.return_date
        FROM borrows br
        JOIN books b ON br.book_id = b.id
        WHERE br.student_id = ?
        ORDER BY br.borrow_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Lịch sử mượn sách</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header>
  <h1>📖 Lịch sử mượn sách</h1>
</header>

<main>
  <?php if (count($history) > 0): ?>
    <table border="1" cellspacing="0" cellpadding="8" style="margin: auto;">
      <tr>
        <th>Tiêu đề sách</th>
        <th>Tác giả</th>
        <th>Ngày mượn</th>
        <th>Ngày trả</th>
      </tr>
      <?php foreach ($history as $record): ?>
      <tr>
        <td><?= htmlspecialchars($record['title']) ?></td>
        <td><?= htmlspecialchars($record['author']) ?></td>
        <td><?= htmlspecialchars($record['borrow_date']) ?></td>
        <td><?= htmlspecialchars($record['return_date']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>Chưa có lịch sử mượn sách nào.</p>
  <?php endif; ?>

  <a href="index.php">Quay lại trang chủ</a>
</main>

<footer>
  <p>&copy; 2025 Thư viện UniBooks</p>
</footer>

</body>
</html>
