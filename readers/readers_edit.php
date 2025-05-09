<?php
require_once('../db.php');
include("../includes/header.php");
include("../includes/nav.php");

$reader = null;
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['student_id'])) {
        $student_id = trim($_GET['student_id']);
        try {
            $stmt = $pdo->prepare("SELECT * FROM readers WHERE student_id = ?");
            $stmt->execute([$student_id]);
            $reader = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$reader) {
                $error_message = "Không tìm thấy độc giả với mã sinh viên này.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi truy vấn CSDL: " . $e->getMessage();
        }
    } else {
        $error_message = "Mã sinh viên không được cung cấp.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $errors = [];
    if (empty($student_id)) $errors[] = "Mã sinh viên bị thiếu.";
    if (empty($name)) $errors[] = "Họ tên không được để trống.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
    if (empty($phone)) $errors[] = "Số điện thoại không được để trống.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE readers SET name = ?, email = ?, phone = ? WHERE student_id = ?");
            $result = $stmt->execute([$name, $email, $phone, $student_id]);
            if ($result) {
                $success_message = "Cập nhật thông tin độc giả thành công!";
                $stmt = $pdo->prepare("SELECT * FROM readers WHERE student_id = ?");
                $stmt->execute([$student_id]);
                $reader = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Không thể cập nhật thông tin độc giả.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    $error_message = "Email đã tồn tại.";
                } elseif (strpos($e->getMessage(), 'phone') !== false) {
                    $error_message = "Số điện thoại đã tồn tại.";
                } else {
                    $error_message = "Lỗi dữ liệu trùng lặp.";
                }
            } else {
                $error_message = "Lỗi CSDL: " . $e->getMessage();
            }
            // Điền lại $reader với dữ liệu POST
            $reader = [
                "student_id" => $student_id,
                "name" => $name,
                "email" => $email,
                "phone" => $phone
            ];
        }
    } else {
        $error_message = implode("<br>", $errors);
        $reader = [
            "student_id" => $student_id,
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ];
    }
}
?>

<main>
  <h2>Chỉnh sửa thông tin độc giả</h2>
  <?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
  <?php endif; ?>
  <?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
  <?php endif; ?>

  <?php if ($reader): ?>
  <form action="readers_edit.php?student_id=<?= urlencode($reader['student_id']) ?>" method="POST" class="form-box">
    <input type="hidden" name="student_id" value="<?= htmlspecialchars($reader['student_id']) ?>">

    <label for="name">Họ tên:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($reader['name']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($reader['email']) ?>" required>

    <label for="phone">Số điện thoại:</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($reader['phone']) ?>" required>

    <input type="submit" value="Cập nhật" class="btn">
    <a href="readers_list.php" class="btn" style="background-color: #aaa;">Hủy</a>
  </form>
  <?php endif; ?>
</main>

<?php include("../includes/footer.php"); ?>