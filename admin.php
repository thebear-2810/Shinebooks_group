<?php
session_start();
include 'config.php';

// 1. Kiểm tra quyền Admin (Bảo mật)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Bạn không có quyền truy cập! <a href='index.php'>Về trang chủ</a>");
}

// 2. Xử lý chức năng XÓA
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Xóa dữ liệu trong CSDL
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    // Quay lại trang admin để refresh danh sách
    header("Location: admin.php");
}

// 3. Xử lý chức năng THÊM MỚI (Có upload ảnh)
if (isset($_POST['add_product'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    
    // Xử lý upload ảnh
    $image = "no-image.jpg"; // Ảnh mặc định
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['name'] != "") {
        $target_dir = "images/";
        // Đặt tên file theo thời gian để tránh trùng tên
        $target_file = $target_dir . time() . "_" . basename($_FILES["fileUpload"]["name"]);
        
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            $image = time() . "_" . basename($_FILES["fileUpload"]["name"]);
        }
    }

    $sql = "INSERT INTO products (title, author, price, image) VALUES ('$title', '$author', '$price', '$image')";
    mysqli_query($conn, $sql);
    header("Location: admin.php"); // Chống gửi lại form khi F5
}

// Lấy danh sách sản phẩm mới nhất lên đầu
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản trị Shinebooks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo"><h2>Admin Dashboard</h2></div>
        <div>
            <a href="index.php" target="_blank">Xem trang chủ</a>
            <a href="login.php?logout=true">Đăng xuất</a>
        </div>
    </nav>

    <div class="container">
        <div style="background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3>Thêm Sách Mới</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Tên sách" required>
                <input type="text" name="author" placeholder="Tác giả" required>
                <input type="number" name="price" placeholder="Giá bán" required>
                
                <div style="margin: 15px 0;">
                    <label>Chọn ảnh bìa:</label>
                    <input type="file" name="fileUpload" accept="image/*" required>
                </div>
                
                <button type="submit" name="add_product" class="btn">Thêm sản phẩm</button>
            </form>
        </div>

        <h3>Danh Sách Sản Phẩm</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 100px;">Hình</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Giá</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <img src="images/<?php echo $row['image']; ?>" width="60" style="object-fit: cover; border: 1px solid #ddd;">
                    </td>
                    <td><strong><?php echo $row['title']; ?></strong></td>
                    <td><?php echo $row['author']; ?></td>
                    <td style="color: red; font-weight: bold;"><?php echo number_format($row['price']); ?>đ</td>
                    <td>
                        <a href="product_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Sửa</a>
                        
                        <a href="admin.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa cuốn sách này không?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>