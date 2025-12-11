<?php
session_start();
include 'config.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Bạn không có quyền truy cập!");
}

// 2. Lấy ID sản phẩm từ URL để đổ dữ liệu ra
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    // Nếu không tìm thấy sách
    if (!$product) {
        die("Sản phẩm không tồn tại!");
    }
} else {
    header("Location: admin.php");
}

// 3. Xử lý khi bấm nút "Cập nhật"
if (isset($_POST['update_product'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price']; // Giá cũ để gạch ngang
    
    // Logic xử lý ảnh:
    // Nếu người dùng chọn ảnh mới -> Upload và dùng tên ảnh mới
    // Nếu người dùng KHÔNG chọn ảnh -> Giữ nguyên tên ảnh cũ trong DB
    
    $image = $product['image']; // Mặc định là ảnh cũ

    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['name'] != "") {
        $target_dir = "images/";
        $target_file = $target_dir . time() . "_" . basename($_FILES["fileUpload"]["name"]);
        
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            $image = time() . "_" . basename($_FILES["fileUpload"]["name"]);
        }
    }

    // Câu lệnh Update
    $sql_update = "UPDATE products SET 
                   title='$title', 
                   author='$author', 
                   price='$price', 
                   old_price='$old_price',
                   image='$image' 
                   WHERE id=$id";
                   
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='admin.php';</script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm - Shinebooks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo"><h2>Admin Dashboard</h2></div>
        <div><a href="admin.php">← Quay lại danh sách</a></div>
    </nav>

    <div class="container">
        <div class="form-container" style="max-width: 600px;">
            <h2 style="text-align: center;">Sửa thông tin sách</h2>
            
            <form method="POST" enctype="multipart/form-data">
                <label>Tên sách:</label>
                <input type="text" name="title" value="<?php echo $product['title']; ?>" required>
                
                <label>Tác giả:</label>
                <input type="text" name="author" value="<?php echo $product['author']; ?>" required>
                
                <label>Giá bán (đ):</label>
                <input type="number" name="price" value="<?php echo $product['price']; ?>" required>

                <label>Giá gốc (đ - để hiển thị gạch ngang):</label>
                <input type="number" name="old_price" value="<?php echo $product['old_price']; ?>">
                
                <label>Ảnh hiện tại:</label><br>
                <img src="images/<?php echo $product['image']; ?>" width="100" style="margin: 10px 0; border: 1px solid #ddd;">
                
                <label>Chọn ảnh mới (Nếu muốn đổi):</label>
                <input type="file" name="fileUpload" accept="image/*">
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="update_product" class="btn btn-warning" style="width: 100%;">Cập nhật thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>