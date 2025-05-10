<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';

// Tạo biến
$books = [];
$error_message = '';

$search_term = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$search_field = filter_input(INPUT_GET, 'field', FILTER_SANITIZE_STRING);

try {
    // Build truy vấn SQL
    $sql = "SELECT stt, title, author, category, publish_year, status, summary, images FROM books";
    $params = [];
    
    // Thêm điều kiện tìm kiếm nếu tham số tìm kiếm tồn tại
    if ($search_term && $search_field) {
        $sql .= " WHERE $search_field LIKE ?";
        $params[] = "%$search_term%";
    }
    
    $sql .= " ORDER BY stt";
    
    // CHuẩn bị và thực hiện query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
        <select name="field">
            <option value="title" <?= $search_field === 'title' ? 'selected' : '' ?>>Tên sách</option>
            <option value="author" <?= $search_field === 'author' ? 'selected' : '' ?>>Tác giả</option>
            <option value="category" <?= $search_field === 'category' ? 'selected' : '' ?>>Thể loại</option>
        </select>
        <input type="text" name="search" value="<?= htmlspecialchars($search_term ?? '') ?>" placeholder="Nhập từ khóa...">
        <button type="submit">Tìm kiếm</button>
    </form>

    <a href="books_add.php" class="btn">+ Thêm sách</a>

    <?php if (!empty($books)): ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>Năm XB</th>
                    <th>Trạng thái</th>
                    <th>Tóm tắt</th>
                    <th>Hình ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['stt']) ?></td>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['category']) ?></td>
                        <td><?= htmlspecialchars($book['publish_year']) ?></td>
                        <td><?= ($book['status'] === 'available') ? 'Sẵn sàng' : 'Đã mượn' ?></td>
                        <td><?= htmlspecialchars($book['summary']) ?></td>
                        <td>
                            <?php if (!empty($book['images'])): ?>
                                <img src="<?= htmlspecialchars($book['images']) ?>" alt="Ảnh bìa" width="60">
                            <?php else: ?>
                                Không có
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="books_edit.php?stt=<?= htmlspecialchars($book['stt']) ?>" 
                               class="btn btn-small">Sửa</a>
                            <a href="books_delete.php?stt=<?= htmlspecialchars($book['stt']) ?>"
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