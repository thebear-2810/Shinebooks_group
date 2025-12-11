<?php
$conn = mysqli_connect("localhost", "root", "", "shinebooks1");
if (!$conn) { die("Kết nối thất bại: " . mysqli_connect_error()); }
mysqli_set_charset($conn, "utf8");
?>