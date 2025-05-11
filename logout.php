<?php
session_start(); // Khởi động session
session_unset(); // Xóa tất cả các session
session_destroy(); // Hủy session

// Chuyển hướng người dùng về trang đăng nhập
header("Location: index.php");
exit;
?>
