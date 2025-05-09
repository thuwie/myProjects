<?php
$host = 'localhost'; 
$dbname = 'library_system'; 
$username = 'root'; 
$password = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Ném exception khi có lỗi
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
     $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
     // Ghi log lỗi trong production
     error_log("Database Connection Error: " . $e->getMessage());
     die("Không thể kết nối đến cơ sở dữ liệu. Vui lòng thử lại sau."); // Thông báo chung
}
?>