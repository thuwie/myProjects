<?php
require_once '../middleware/auth.php';
require_once '../database/db.php';
require_once '../helpers/normalization.php';

$book = null;
$error_message = '';
$success_message = '';

// Xử lý request của GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $book_id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$book) {
                $error_message = "Không tìm thấy sách!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi truy vấn dữ liệu: " . $e->getMessage();
        }
    } else {
        $error_message = "Sách không hợp lệ.";
    }
}

// Xử lý request của POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_GET['id'];
    $images= ""; 
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $publish_year = filter_input(INPUT_POST, 'publish_year', FILTER_VALIDATE_INT);
    $summary = trim($_POST['summary'] ?? '');
    $status = $_POST['status'];
    $fieldsToUpdate = [];
    $params = [];

    //Tạo đường dẫn thư mục nơi chứa ảnh
    $uploadDir = "uploads/"; // thư mục lưu ảnh
    $fileName = time() . "_" . basename($_FILES["newImage"]["name"]);
    $targetFile = $uploadDir . $fileName;

    $fields = ['images', 'title', 'author', 'category', 'publish_year', 'summary', 'status'];
    $valuesFromClient = [$targetFile, $title, $author, $category, $publish_year, $summary, $status];
    $clientData = array_combine($fields, $valuesFromClient);
    $comma = "";
    
    //Select book được chọn để update
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $dbBook = $stmt->fetch(PDO::FETCH_ASSOC);

    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    };

    //TÌm ra những fields cần cập nhật trên phía client
   foreach ($clientData as $key => $value) {
        if($key == array_key_last($clientData) && count($fieldsToUpdate) < count($clientData))
        {
            $lastKey = array_key_last($fieldsToUpdate); //Tìm last key
            $lastItem = $fieldsToUpdate[$lastKey]; //Tìm last item
            $pos = strpos($lastItem, ","); //Kiểm tra xem phần tử cuối có chứa dấu , không
            if($pos) {
                  $newItem = str_replace(',', '', $lastItem);
                  $fieldsToUpdate[$lastKey] = $newItem;
            }; 
        } else {
             $comma = ", ";
        };
        if (!empty($value) && normalize_input($value) !== normalize_input($dbBook[$key])) {
            if($key == array_key_last($clientData)) {
                $comma = "" . " where id = ?";
            };  

            $fieldsToUpdate[] = $key . " = ?" .  $comma ;
            $params[] = $value;
            if ($key !== array_key_last($clientData)) {
                $params[] = $book_id;
            };
        };
    };
    
     if(strpos($fieldsToUpdate[0], 'images')) {
        // Di chuyển file từ temp (là đường dẫn tạm thời trên server) vào thư mục đích
        if (move_uploaded_file($_FILES["newImage"]["tmp_name"], $targetFile)) {
            // Lưu đường dẫn vào DB
            $images = $targetFile;
        };
    }; 

    
    // Bước kiểm tra dữ liệu
    $errors = [];
    if (!$book_id) $errors[] = "Sách không hợp lệ.";
    if (empty($title)) $errors[] = "Tên sách không được để trống.";
    if (empty($author)) $errors[] = "Tác giả không được để trống.";
    if (empty($category)) $errors[] = "Thể loại không được để trống.";
    if (!$publish_year || $publish_year < 1000 || $publish_year > (date('Y') + 1)) {
        $errors[] = "Năm xuất bản không hợp lệ.";
    }
    if (!in_array($status, ['available', 'borrowed'])) {
        $errors[] = "Trạng thái không hợp lệ.";
    };


    if (empty($errors) && !empty($fieldsToUpdate)) {
        try {
            $sql =  "UPDATE books SET " . implode('', $fieldsToUpdate);
            echo $sql;
            print_r ($params);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                $success_message = "Cập nhật thông tin sách thành công!";
                header('Location: ./books_list.php');
                exit;
            } else {
                $error_message = "Không thể cập nhật thông tin sách.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi database: " . $e->getMessage();
        }
    } else {
        $error_message = implode("<br>", $errors);
        // Điền lại $book với dữ liệu POST
        $book = [
            'id' => $book_id,
            'images' => $images,
            'title' => $title,
            'author' => $author,
            'category' => $category,
            'publish_year' => $publish_year,
            'summary' => $summary,
            'status' => $status
        ];
    };
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/nav.php"); ?>

<main>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/styles.css">
    <h2>Chỉnh sửa thông tin sách</h2>

    <?php if ($error_message): ?>
        <p style="color: red; font-weight:bold;"><?= $error_message ?></p>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <p style="color: green; font-weight:bold;"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($book): ?>
        <form class="form-edit-book" action="books_edit.php?id=<?= htmlspecialchars($book['id']) ?>" 
            method="POST" enctype="multipart/form-data">

            <div class="container-edit-book">
                <div class="form-group-img">
                    <label>Ảnh:</label><br>
                    <img class ="images" name="image" src="<?= htmlspecialchars($book['images']) ?>"><br><br>
                    <button type ="button" class="btn-change-img">Chọn để thay đổi ảnh</button>
                    <input type ="file" class ="newImage" name ="newImage" hidden>
                </div>
                
                <div class="information-edit-book">
                    <div class="form-group-information">
                        <label for="title">Tên sách:</label>
                        <input type="text" id="title" name="title" 
                        value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    
                    <div class="form-group-information">
                        <label for="author">Tác giả:</label>
                        <input type="text" id="author" name="author" 
                        value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label for="category">Thể loại:</label>
                        <input type="text" id="category" name="category" 
                        value="<?= htmlspecialchars($book['category']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label for="publish_year">Năm xuất bản:</label>
                        <input type="number" id="publish_year" name="publish_year" 
                            min="1000" max="<?= date('Y') + 1 ?>" 
                            value="<?= htmlspecialchars($book['publish_year']) ?>" required>
                    </div>

                    <div class="form-group-information">
                        <label>Tóm tắt:</label><br>
                        <textarea name="summary" rows="4" cols="50"><?= htmlspecialchars($book['summary']) ?></textarea><br><br>
                    </div>

                    <div class="form-group-information">
                        <label for="status">Trạng thái:</label>
                        <select id="status" name="status" required>
                            <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>
                                Sẵn sàng
                            </option>
                            <option value="borrowed" <?= $book['status'] === 'borrowed' ? 'selected' : '' ?>>
                                Đã được mượn
                            </option>
                        </select>
                    </div>

                    <div class="form-group-information">
                        <input type="submit" value="Cập nhật" class="btn">
                        <a href="books_list.php" class="btn" style="background-color: #aaa;">Hủy</a>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <script src ="../validation/action.js"></script>
</main>

<?php include("../includes/footer.php"); ?>