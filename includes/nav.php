<?php
if (!isset($base_path)) {
    $base_path = '';
}
?>
<nav>
  <ul>
  <li>
        <li><a href="<?= $base_url ?>index.php">Trang Chủ</a></li>
        <li><a href="<?= $base_url ?>books/books_list.php">Quản lý sách</a></li>
        <li><a href="<?= $base_url ?>readers/readers_list.php">Quản lý độc giả</a></li>
        <li><a href="<?= $base_url ?>loans/loans_borrow.php">Mượn / Trả sách</a></li>
  </ul>
</nav>