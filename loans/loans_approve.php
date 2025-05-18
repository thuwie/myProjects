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
        // Lấy thông tin phiếu mượn 
        $stmt = $pdo->prepare("SELECT book_id FROM loans WHERE id = ? AND status = 'pending'");
        $stmt->execute([$loan_id]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($loan) {
            $pdo->beginTransaction();

            $approved_by = $_SESSION['username'] ?? 'admin';

            // Cập nhật trạng thái phiếu mượn thành 'approved' + thời gian/người duyệt
            $stmt = $pdo->prepare("UPDATE loans SET status = 'approved', approved_at = NOW(), approved_by = ? WHERE id = ?");
            $stmt->execute([$approved_by, $loan_id]);

            // Cập nhật trạng thái sách thành 'borrowed'
            $stmt = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?");
            $stmt->execute([$loan['book_id']]);

            $pdo->commit();

            header("Location: loans_borrow.php?msg=approved");
            exit;
        } else {
            header("Location: loans_borrow.php?error=invalid_loan");
            exit;
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Lỗi khi duyệt phiếu mượn ID $loan_id: " . $e->getMessage()); // Ghi log để debug
        header("Location: loans_borrow.php?error=exception");
        exit;
    }
} else {
    header("Location: loans_borrow.php?error=invalid_request");
    exit;
}
