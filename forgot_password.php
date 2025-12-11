<?php
include 'config.php';
$msg = "";
if (isset($_POST['reset'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_pass = md5($_POST['new_password']);
    
    // Kiểm tra user có tồn tại với email đó không
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND email='$email'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE username='$username'");
        $msg = "Đổi mật khẩu thành công! <a href='login.php'>Đăng nhập lại</a>";
    } else {
        $msg = "Thông tin không chính xác!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="form-container">
        <h2>Lấy lại mật khẩu</h2>
        <?php echo "<p>$msg</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="email" name="email" placeholder="Email đăng ký" required>
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            <button type="submit" name="reset" class="btn btn-warning" style="width:100%">Đổi mật khẩu</button>
        </form>
    </div>
</body>
</html>