<?php
require_once('../db.php');

$error_message = '';
$success_message = '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);
    
    try {
        $pdo->beginTransaction();
        
        // Kiểm tra sách có tồn tại và có đang được mượn không
        $stmt = $pdo->prepare("SELECT status FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        
        if (!$book) {
            throw new Exception("Sách không tồn tại.");
        }
        
        if ($book['status'] === 'borrowed') {
            throw new Exception("Không thể xóa sách đang được mượn.");
        }
        
        // Kiểm tra còn phiếu mượn nào chưa trả không
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM loans WHERE book_id = ? AND actual_return IS NULL");
        $stmt->execute([$book_id]);
        $has_unreturned = $stmt->fetchColumn() > 0;
        
        if ($has_unreturned) {
            throw new Exception("Không thể xóa sách còn phiếu mượn chưa trả.");
        }

        // Xóa tất cả lịch sử mượn của sách này (nếu muốn giữ lịch sử thì bỏ dòng này)
        $stmt = $pdo->prepare("DELETE FROM loans WHERE book_id = ?");
        $stmt->execute([$book_id]);
        
        // Xóa sách
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        
        $pdo->commit();
        $success_message = "Đã xóa sách thành công.";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = $e->getMessage();
    }
} else {
    $error_message = "ID sách không hợp lệ.";
}

// Chuyển hướng
if ($error_message) {
    header("Location: books_list.php?error=" . urlencode($error_message));
} else {
    header("Location: books_list.php?success=" . urlencode($success_message));
}
exit();