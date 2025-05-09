<?php
require_once('../db.php');
include("../includes/header.php");
include("../includes/nav.php");

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $errors = [];
    if (empty($student_id)) $errors[] = "Mã sinh viên không được để trống.";
    if (empty($name)) $errors[] = "Họ tên không được để trống.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
    if (empty($phone)) $errors[] = "Số điện thoại không được để trống.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO readers (student_id, name, email, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_id, $name, $email, $phone]);
            $success_message = "Thêm độc giả thành công!";
            $student_id = $name = $email = $phone = '';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    $error_message = "Email đã tồn tại.";
                } elseif (strpos($e->getMessage(), 'phone') !== false) {
                    $error_message = "Số điện thoại đã tồn tại.";
                } elseif (strpos($e->getMessage(), 'PRIMARY') !== false) {
                    $error_message = "Mã sinh viên đã tồn tại.";
                } else {
                    $error_message = "Lỗi dữ liệu trùng lặp.";
                }
            } else {
                $error_message = "Lỗi CSDL: " . $e->getMessage();
            }
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>

<main>
  <h2>Thêm độc giả mới</h2>
  <?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
  <?php endif; ?>
  <?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
  <?php endif; ?>

  <form action="readers_add.php" method="POST" class="form-box">
    <label for="student_id">Mã sinh viên:</label>
    <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($student_id ?? '') ?>" required>

    <label for="name">Họ tên:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

    <label for="phone">Số điện thoại:</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" required>

    <input type="submit" value="Lưu độc giả" class="btn">
  </form>
</main>

<?php include("../includes/footer.php"); ?>