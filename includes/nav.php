<?php
if (!isset($base_path)) {
    $base_path = '';
}
?>
<nav>
  <ul>
  <li>
        <li><a href="<?= $base_url ?>index.php">Trang Chủ</a></li>
        <li><a href="<?= $base_url ?>books/books_list.php">Quản Lý Sách</a></li>
        <li><a href="<?= $base_url ?>users/user_list.php">Quản Lý Đọc giả</a></li>
        <li><a href="<?= $base_url ?>loans/loans_borrow.php">Mượn / Trả Sách</a></li>
  </ul>
</nav>