<?php
session_start();
require_once '../database/db.php';
include("../includes/header.php");
include("../includes/nav.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

$keyword = $_GET['keyword'] ?? '';
$search_field = $_GET['search_field'] ?? 'masv';

if ($keyword !== '') {
    $sql = "SELECT * FROM users WHERE role = 'user' AND $search_field LIKE :keyword";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['keyword' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
}
$users = $stmt->fetchAll();
?>

<main>
    <link rel="stylesheet" href="../assets/users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <h2>Danh sách Đọc Giả</h2>

    <a href="user_add.php" class="btn btn-success" style="margin-bottom: 15px;">+ Thêm Đọc Giả</a>

    <form method="GET" action="" style="display: inline-block; vertical-align: middle;">
        <input type="text" name="keyword" placeholder="Nhập mã sinh viên..." value="<?= htmlspecialchars($keyword) ?>" style="padding: 5px; width: 200px;">
        <button type="submit" style="padding: 5px 10px;">Tìm kiếm</button>
    </form>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['masv']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td class="action-icons">
                            <a href="user_delete.php?masv=<?= urlencode($row['masv']) ?>" class="delete-icon" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include("../includes/footer.php"); ?>
