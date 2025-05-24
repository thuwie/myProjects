<?php
$base_url = '/'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thư viện</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/styles.css">
</head>
<body>
    <header>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <h1>📚 Danh sách sách hiện có:</h1>
        <?php else: ?>
            <h1>📖 Hệ thống quản lý thư viện 📖</h1>
        <?php endif; ?>
        <span class="user-avt">
            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>
            <i class="fa-solid fa-user"></i>
        </span>
    </header>