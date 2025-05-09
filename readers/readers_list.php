<?php
require_once('../db.php');

$readers = [];
$error_message = '';

try {
    $stmt = $pdo->query("SELECT student_id, name, email, phone FROM readers ORDER BY name");
    $readers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi khi truy vấn CSDL: " . $e->getMessage();
}
?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/nav.php"); ?>

<main>
  <h2>Danh sách độc giả</h2>
  <a href="readers_add.php" class="btn">+ Thêm độc giả</a>

  <?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
  <?php endif; ?>

  <?php if (!empty($readers)): ?>
  <table>
    <thead>
      <tr>
        <th>Mã SV</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Điện thoại</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($readers as $reader): ?>
      <tr>
        <td><?= htmlspecialchars($reader['student_id']) ?></td>
        <td><?= htmlspecialchars($reader['name']) ?></td>
        <td><?= htmlspecialchars($reader['email']) ?></td>
        <td><?= htmlspecialchars($reader['phone']) ?></td>
        <td>
          <a href="readers_edit.php?student_id=<?= urlencode($reader['student_id']) ?>" class="btn btn-small">Sửa</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>Hiện chưa có độc giả nào được đăng ký.</p>
  <?php endif; ?>
</main>

<?php include("../includes/footer.php"); ?>