<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';

$book = null;
$error_message = '';
$success_message = '';

// Xử lý request của GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $book_id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$book) {
                $error_message = "Không tìm thấy sách!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi truy vấn dữ liệu: " . $e->getMessage();
        }
    } else {
        $error_message = "Sách không hợp lệ.";
    }
}

// Xử lý request của POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $images = filter_input(INPUT_POST, 'images', FILTER_SANITIZE_URL);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $publish_year = filter_input(INPUT_POST, 'publish_year', FILTER_VALIDATE_INT);
    $summary = trim($_POST['summary'] ?? '');

    // Bước kiểm tra dữ liệu
    $errors = [];
    if (!$book_id) $errors[] = "Sách không hợp lệ.";
    if (empty($title)) $errors[] = "Tên sách không được để trống.";
    if (empty($author)) $errors[] = "Tác giả không được để trống.";
    if (empty($category)) $errors[] = "Thể loại không được để trống.";
    if (!$publish_year || $publish_year < 1000 || $publish_year > (date('Y') + 1)) {
        $errors[] = "Năm xuất bản không hợp lệ.";
    }
    if (!in_array($status, ['available', 'borrowed'])) {
        $errors[] = "Trạng thái không hợp lệ.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE books SET images = ?, title = ?, author = ?, category = ?, 
                                 publish_year = ?, summary = ?, status = ? WHERE id = ?");
            $result = $stmt->execute([$images, $title, $author, $category, $publish_year, $summary, $status, $book_id]);
            
            if ($result) {
                $success_message = "Cập nhật thông tin sách thành công!";
                // Lấy dữ liệu đã cập nhật
                $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
                $stmt->execute([$book_id]);
                $book = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Không thể cập nhật thông tin sách.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi database: " . $e->getMessage();
        }
    } else {
        $error_message = implode("<br>", $errors);
        // Điền lại $book với dữ liệu POST
        $book = [
            'id' => $book_id,
            'images' => $images,
            'title' => $title,
            'author' => $author,
            'category' => $category,
            'publish_year' => $publish_year,
            'summary' => $summary,
            'status' => $status
        ];
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/nav.php"); ?>

<main>
    <link rel="stylesheet" href="../assets/styles.css">
    <h2>Chỉnh sửa thông tin sách</h2>

    <?php if ($error_message): ?>
        <p style="color: red; font-weight:bold;"><?= $error_message ?></p>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <p style="color: green; font-weight:bold;"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($book): ?>
        <form action="books_edit.php?id=<?= htmlspecialchars($book['id']) ?>" method="POST" class="form-edit-book">
            <div class="container-edit-book">
                <div class="form-group-img">
                    <label>Ảnh:</label><br>
                    <img name="images" src="<?= htmlspecialchars($book['images']) ?>"><br><br>
                     <button class="btn-change-img">Chọn để thay đổi ảnh</button>
                    <input type ="file" class ="newImage" name ="newImage" hidden>
                </div>
                
                <div class="information-edit-book">
                    <div class="form-group-information">
                        <label for="title">Tên sách:</label>
                        <input type="text" id="title" name="title" 
                        value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    
                    <div class="form-group-information">
                        <label for="author">Tác giả:</label>
                        <input type="text" id="author" name="author" 
                        value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label for="category">Thể loại:</label>
                        <input type="text" id="category" name="category" 
                        value="<?= htmlspecialchars($book['category']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label for="publish_year">Năm xuất bản:</label>
                        <input type="number" id="publish_year" name="publish_year" 
                            min="1000" max="<?= date('Y') + 1 ?>" 
                            value="<?= htmlspecialchars($book['publish_year']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label>Tóm tắt:</label><br>
                        <textarea name="summary" rows="4" cols="50"><?= htmlspecialchars($book['summary']) ?></textarea><br><br>
                    </div>

                    <div class="form-group-information">
                        <label for="status">Trạng thái:</label>
                        <select id="status" name="status" required>
                            <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>
                                Sẵn sàng
                            </option>
                            <option value="borrowed" <?= $book['status'] === 'borrowed' ? 'selected' : '' ?>>
                                Đã được mượn
                            </option>
                        </select>
                    </div>

                    <div class="form-group-information">
                        <input type="submit" value="Cập nhật" class="btn">
                        <a href="books_list.php" class="btn" style="background-color: #aaa;">Hủy</a>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <script src ="../validation/action.js"></script>
</main>

<?php include("../includes/footer.php"); ?>