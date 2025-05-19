<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
// Kiểm tra ID phiếu mượn là số nguyên
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $loan_id = intval($_GET['id']);

    try {
        // Lấy phiếu mượn cần trả, chỉ xử lý khi status = 'approved'
        $stmt = $pdo->prepare("SELECT book_id FROM loans WHERE id = ? AND status = 'approved'");
        $stmt->execute([$loan_id]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($loan) {
            $pdo->beginTransaction();

            // Cập nhật status phiếu mượn thành 'returned'
            $stmt = $pdo->prepare("UPDATE loans SET status = 'returned' WHERE id = ?");
            $stmt->execute([$loan_id]);

            // Cập nhật trạng thái sách thành 'available'
            $stmt = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
            $stmt->execute([$loan['book_id']]);

            $pdo->commit();

            header("Location: loans_borrow.php?msg=returned");
            exit;
        } else {
            header("Location: loans_borrow.php?error=invalid_loan_or_status");
            exit;
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Lỗi khi trả sách phiếu mượn ID $loan_id: " . $e->getMessage());
        header("Location: loans_borrow.php?error=exception");
        exit;
    }
} else {
    header("Location: loans_borrow.php?error=invalid_request");
    exit;
}
