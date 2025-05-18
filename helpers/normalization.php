<?php
    function normalize_input($value, $target_encoding = 'UTF-8') {
    // Bước 1: Nếu null thì trả về chuỗi rỗng
    if (is_null($value)) {
        return '';
    }

    // Bước 2: Nếu là kiểu số thì ép kiểu thành chuỗi
    if (is_numeric($value)) {
        return (string) $value;
    }

    // Bước 3: Trim – loại bỏ khoảng trắng đầu/cuối chuỗi
    $value = trim($value);

    // Bước 4: Chuẩn hóa encoding (nếu không phải UTF-8)
    if (mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true) !== $target_encoding) {
        $value = mb_convert_encoding($value, $target_encoding, 'auto');
    }

    // Bước 5: Loại bỏ các ký tự điều khiển không mong muốn
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value); // bỏ ký tự không in được

    return $value;
    };

?>