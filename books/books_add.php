<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';
include("../includes/header.php");
include("../includes/nav.php");

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dự liệu từ POST
    $images = filter_input(INPUT_POST, 'images', FILTER_SANITIZE_URL);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $publish_year = filter_input(INPUT_POST, 'publish_year', FILTER_VALIDATE_INT, 
        ["options" => ["min_range" => 1000, "max_range" => date('Y') + 1]]);
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = $_POST['status'] ?? 'available';
    
    // Xác thực dữ liệu
    $errors = [];
    if (empty($title)) $errors[] = "Tên sách không được để trống";
    if (empty($author)) $errors[] = "Tác giả không được để trống";
    if (empty($category)) $errors[] = "Thể loại không được để trống";
    if ($publish_year === false) $errors[] = "Năm xuất bản không hợp lệ";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (images, title, author, category, publish_year, summary, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $author, $category, $publish_year, $status, $summary, $images]);
            
            if ($result) {
                $success_message = "Thêm sách thành công!";
                // Xóa dữ liệu form
                $images = $title = $author = $category = $publish_year = $summary = $status = '';
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
        <div class="container-box">
            <div class="form-group img-file">
                <label>Chọn hình ảnh:</label><br>
                <input type="file" name="images"><br><br>
            </div>

            <div class="form-group">
                 <label for="title">Tên sách:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($title ?? '') ?>" required>
            </div>
           
            <div class="form-group">
                 <label for="author">Tác giả:</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($author ?? '') ?>" required>
            </div>
           
            <div class="form-group">
                <label for="category">Thể loại:</label>
                <input type="text" id="category" name="category" value="<?= htmlspecialchars($category ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="publish_year">Xuất bản:</label>
                <input class="publish_year" type="number" id="publish_year" name="publish_year" 
                    min="1000" max="<?= date('Y') + 1 ?>" 
                    value="<?= htmlspecialchars($publish_year ?? '') ?>" required>
            </div>

            <div class="form-group desctiption">
                <label>Tóm tắt:</label><br>
                <textarea name="summary" rows="4" cols="50"></textarea><br><br>
            </div>

            <div class="form-group status">
                <label>Trạng thái:</label><br>
                <select name="status">
                    <option value="available">Có sẵn</option>
                    <option value="unavailable">Đã mượn</option>
                </select><br><br>
            </div>
            
            <input type="submit" value="Thêm sách" class="btn">
        </div>
    </form>
</main>

<?php include("../includes/footer.php"); ?>