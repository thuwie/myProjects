<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';

// Tạo biến
$books = [];
$error_message = '';

$search_term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
$search_field = filter_input(INPUT_GET, 'field', FILTER_SANITIZE_STRING);

try {
    // Build truy vấn SQL
    $sql = "SELECT id, images, title, author, category, publish_year, summary, status FROM books";
    $params = [];
    
    // Thêm điều kiện tìm kiếm nếu tham số tìm kiếm tồn tại
    if ($search_term && $search_field && in_array($search_field, ['title', 'author', 'category'])) {
        $sql .= " WHERE $search_field LIKE ?";
        $params[] = "%$search_term%";
    }
    
    $sql .= " ORDER BY id";
    
    // CHuẩn bị và thực hiện query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT DISTINCT category FROM books ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    } catch (PDOException $e) {
        $error_message = "Lỗi truy vấn dữ liệu: " . $e->getMessage();
}

?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/nav.php"); ?>

<main>
    <h2>📚 Danh sách sách</h2>
    
    <?php if ($error_message): ?>
        <p style="color: red; font-weight: bold;"><?= $error_message ?></p>
    <?php endif; ?>

    <form action="books_list.php" method="GET" class="search-form">
        <select name="field" id="search-field" onchange="toggleSearchInput()">
            <option value="title" <?= $search_field === 'title' ? 'selected' : '' ?>>Tên sách</option>
            <option value="author" <?= $search_field === 'author' ? 'selected' : '' ?>>Tác giả</option>
            <option value="category" <?= $search_field === 'category' ? 'selected' : '' ?>>Thể loại</option>
        </select>
        <!-- Input text -->
        <input type="text" name="term" id="search-text" 
            value="<?= htmlspecialchars($search_term ?? '') ?>" 
            placeholder="Nhập từ khóa...">
        <!-- Select combobox cho category -->
        <select name="term" id="search-category" style="display: none;">
            <option value="">-- Chọn thể loại --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" 
                    <?= ($search_term ?? '') === $cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Tìm kiếm</button>
    </form>

    <script>
        function toggleSearchInput() {
            const field = document.getElementById('search-field').value;
            const textInput = document.getElementById('search-text');
            const categorySelect = document.getElementById('search-category');

            if (field === 'category') {
                textInput.style.display = 'none';
                textInput.disabled = true;

                categorySelect.style.display = 'inline-block';
                categorySelect.disabled = false;
            } else {
                textInput.style.display = 'inline-block';
                textInput.disabled = false;

                categorySelect.style.display = 'none';
                categorySelect.disabled = true;
            }
        }

        document.addEventListener('DOMContentLoaded', toggleSearchInput);
    </script>

    <a href="books_add.php" class="btn">+ Thêm sách</a>

    <?php if (!empty($books)): ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Hình ảnh</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>Năm XB</th>
                    <th>Tóm tắt</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id']) ?></td>
                        <td>
                            <?php if (!empty($book['images'])): ?>
                                <img src="<?= htmlspecialchars($book['images']) ?>" alt="Ảnh bìa" width="60">
                            <?php else: ?>
                                Không có
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['category']) ?></td>
                        <td><?= htmlspecialchars($book['publish_year']) ?></td>
                        <td><?= htmlspecialchars($book['summary']) ?></td>
                        <td><?= ($book['status'] === 'available') ? 'Sẵn sàng' : 'Đã mượn' ?></td>
                        
                        <td>
                            <a href="books_edit.php?id=<?= htmlspecialchars($book['id']) ?>" 
                               class="btn btn-small">Sửa</a>
                            <a href="books_delete.php?id=<?= htmlspecialchars($book['id']) ?>"
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy sách nào.</p>
    <?php endif; ?>
</main>

<?php include("../includes/footer.php"); ?>