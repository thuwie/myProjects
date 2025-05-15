<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';
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
    $summary = trim($_POST['summary'] ?? '');

    //Tạo đường dẫn thư mục nơi chứa ảnh
    $uploadDir = "uploads/"; // thư mục lưu ảnh
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $uploadDir . $fileName;

    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    };

    // Di chuyển file từ temp (là đường dẫn tạm thời trên server) vào thư mục đích
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Lưu đường dẫn vào DB
        $imagePath = $targetFile;
    
        // Xác thực dữ liệu
        $errors = [];
        if (empty($title)) $errors[] = "Tên sách không được để trống";
        if (empty($author)) $errors[] = "Tác giả không được để trống";
        if (empty($category)) $errors[] = "Thể loại không được để trống";
        if ($publish_year === false) $errors[] = "Năm xuất bản không hợp lệ";

        if (empty($errors)) {
            try {
                // Tạo mã sách tự động theo thể loại
                $prefix = $category;
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE category = ?");
                $stmt->execute([$category]);
                $count = $stmt->fetchColumn();
                $nextNumber = $count + 1;
                $book_id = $prefix . '_' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT); // VD: TK_01

                $status = 'available';

                $stmt = $pdo->prepare("INSERT INTO books (id, images, title, author, category, publish_year, summary) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([$book_id, $imagePath, $title, $author, $category, $publish_year, $summary]);
                
                if ($result) {
                    $success_message = "Thêm sách thành công!";
                    $images = $title = $author = $category = $publish_year = $summary = $status = '';
                } else {
                    $error_message = "Có lỗi xảy ra khi thêm sách.";
                }
            } catch (PDOException $e) {
                $error_message = "Lỗi database: " . $e->getMessage();
            }
        } else {
            $error_message = implode("<br>", $errors);
        };
    } else {
        echo "Upload thất bại.";
    };
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

    <form class="form-box" action="books_add.php" method="POST" enctype="multipart/form-data">
        <div class="container-box">
            <div class="form-group img-file">
                <label>Chọn hình ảnh: </label><br>
                <input type="file" name="image"><br><br>
            </div>

            <div class="form-group">
                 <label for="title">Tên sách: </label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($title ?? '') ?>" required>
            </div>
           
            <div class="form-group">
                 <label for="author">Tác giả: </label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($author ?? '') ?>" required>
            </div>
           
            <div class="form-group">
                <label for="category">Thể loại: </label>
                <select name="category" id="category">
                    <option value="TK">Sách tham khảo</option>
                    <option value="KN">Sách kỹ năng sống</option>
                    <option value="VH">Sách văn học</option>
                    <option value="TH">Sách triết học</option>
                    <option value="TL">Sách tâm lý</option>
                    <option value="TR">Sách trinh thám - hình sự</option>
                    <option value="GT">Sách giả tưởng</option>
                    <option value="KHVT">Sách khoa học viễn tưởng</option>
                    <option value="TN">Sách thiếu nhi</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="publish_year">Năm xuất bản: </label>
                <input class="publish_year" type="number" id="publish_year" name="publish_year" 
                    min="1000" max="<?= date('Y') + 1 ?>" 
                    value="<?= htmlspecialchars($publish_year ?? '') ?>" required>
            </div>

            <div class="form-group desctiption">
                <label>Tóm tắt: </label><br>
                <textarea name="summary" rows="2" cols="50"></textarea><br><br>
            </div>
            
            <input type="submit" value="Thêm sách" class="btn">
        </div>
    </form>
</main>

<?php include("../includes/footer.php"); ?>