<?php
session_start();
require_once '../database/db.php'; // file có biến $pdo

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

if (isset($_GET['masv'])) {
    $masv = $_GET['masv'];

    $sql = "DELETE FROM users WHERE masv = ? AND role = 'user'";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$masv]);

    if ($success) {
        header('Location: user_list.php');
        exit();
    } else {
        echo "Lỗi khi xóa người dùng.";
    }
} else {
    header('Location: user_list.php');
    exit();
}
