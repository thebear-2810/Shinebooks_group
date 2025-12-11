<?php
session_start();
include 'config.php';

// Xử lý đăng xuất
if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); }

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Mã hóa MD5 theo yêu cầu đề bài
    
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'admin') { header("Location: admin.php"); } 
        else { header("Location: index.php"); }
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="form-container">
        <h2 style="text-align: center;">Đăng nhập Shinebooks</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login" class="btn" style="width:100%">Đăng nhập</button>
        </form>
        <p style="text-align: center; margin-top: 10px;">
            <a href="forgot_password.php">Quên mật khẩu?</a>
        </p>
    </div>
</body>
</html>