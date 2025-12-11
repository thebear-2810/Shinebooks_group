<?php
// config.php

// Kiểm tra xem có biến môi trường trên Azure không, nếu không thì dùng localhost (máy nhà)
$host = getenv('DB_HOST') ? getenv('DB_HOST') : "localhost";
$user = getenv('DB_USER') ? getenv('DB_USER') : "root";
$pass = getenv('DB_PASS') ? getenv('DB_PASS') : "";
$db   = getenv('DB_NAME') ? getenv('DB_NAME') : "shinebooks";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>
