<?php
// connectSQL.php

$servername = "localhost"; // Địa chỉ máy chủ MySQL (thường là localhost)
$username = "root";        // Tên người dùng MySQL (thường là root)
$password = "";            // Mật khẩu MySQL (nếu có)
$dbname = "qlcoffee"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
