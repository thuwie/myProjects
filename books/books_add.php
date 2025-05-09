<?php
require_once('../db.php');
include("../includes/header.php");
include("../includes/nav.php");

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dự liệu từ POST
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $publish_year = filter_input(INPUT_POST, 'publish_year', FILTER_VALIDATE_INT, 
        ["options" => ["min_range" => 1000, "max_range" => date('Y') + 1]]);
    $status = $_POST['status'] ?? 'available';
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $images = filter_input(INPUT_POST, 'images', FILTER_SANITIZE_URL);
    
    // Xác thực dữ liệu
    $errors = [];
    if (empty($title)) $errors[] = "Tên sách không được để trống";
    if (empty($author)) $errors[] = "Tác giả không được để trống";
    if (empty($category)) $errors[] = "Thể loại không được để trống";
    if ($publish_year === false) $errors[] = "Năm xuất bản không hợp lệ";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (title, author, category, publish_year, status, summary, images) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $author, $category, $publish_year, $status, $summary, $images]);
            
            if ($result) {
                $success_message = "Thêm sách thành công!";
                // Xóa dữ liệu form
                $title = $author = $category = $publish_year = $status = $summary = $images = '';
            } else {
                $error_message = "Có lỗi xảy ra khi thêm sách.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi database: " . $e->getMessage();
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>

<main>
    <h2>Thêm sách mới</h2>
    
    <?php if ($error_message): ?>
        <p style="color: red; font-weight: bold;"><?= $error_message ?></p>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <p style="color: green; font-weight: bold;"><?= $success_message ?></p>
    <?php endif; ?>

    <form action="books_add.php" method="POST" class="form-box">
        <label for="title">Tên sách:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($title ?? '') ?>" required>

        <label for="author">Tác giả:</label>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($author ?? '') ?>" required>

        <label for="category">Thể loại:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($category ?? '') ?>" required>

        <label for="publish_year">Năm xuất bản:</label>
        <input type="number" id="publish_year" name="publish_year" 
               min="1000" max="<?= date('Y') + 1 ?>" 
               value="<?= htmlspecialchars($publish_year ?? '') ?>" required>

        <label>Trạng thái:</label><br>
        <select name="status">
            <option value="available">Còn</option>
            <option value="unavailable">Hết</option>
        </select><br><br>

        <label>Tóm tắt:</label><br>
        <textarea name="summary" rows="4" cols="50"></textarea><br><br>

        <label>Link hình ảnh (URL):</label><br>
        <input type="url" name="images"><br><br>
        
        <input type="submit" value="Thêm sách" class="btn">
    </form>
</main>

<?php include("../includes/footer.php"); ?>