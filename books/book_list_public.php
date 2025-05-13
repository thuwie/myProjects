<?php
session_start();

// Chỉ cho phép người dùng đã đăng nhập và là sinh viên mới truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../index.php');
    exit;
}

require_once '../database/db.php';

// Truy vấn danh sách sách
$sql = "SELECT stt, images, title, author, category, summary, status FROM books";
$stmt = $pdo->query($sql);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách sách</title>
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
      </tr>
      <?php foreach ($books as $book): ?>
        <tr>
          <td><?php echo htmlspecialchars($book['stt']); ?></td>
          <td>
            <?php if (!empty($book['images'])): ?>
              <img src="<?= htmlspecialchars($book['images']) ?>" alt="Ảnh bìa" width="60">
            <?php else: ?>
              Không có
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($book['title']); ?></td>
          <td><?php echo htmlspecialchars($book['author']); ?></td>
          <td><?php echo htmlspecialchars($book['category']); ?></td>
          <td><?= htmlspecialchars($book['summary']) ?></td>
          <td><?= ($book['status'] === 'available') ? 'Sẵn sàng' : 'Đã mượn' ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
      <p>Không có sách nào trong hệ thống.</p>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 20px;">
      <a href="../index.php">Quay lại trang chủ</a>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Thư viện trường XYZ</p>
  </footer>
</body>
</html>
