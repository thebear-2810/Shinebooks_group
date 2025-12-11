<?php
include 'config.php';
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['title']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn">← Quay lại</a>
        <div style="display: flex; margin-top: 20px; background: #fff; padding: 20px;">
            <img src="images/<?php echo $product['image']; ?>" width="300" onerror="this.src='https://via.placeholder.com/300'">
            <div style="margin-left: 30px;">
                <h1><?php echo $product['title']; ?></h1>
                <p>Tác giả: <b><?php echo $product['author']; ?></b></p>
                <p>Nhà xuất bản: <?php echo $product['publisher']; ?></p>
                <p class="price" style="font-size: 24px;"><?php echo number_format($product['price']); ?>đ</p>
                <p>Thể loại: <?php echo $product['category']; ?></p>
                <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn">Thêm vào giỏ</a>
            </div>
        </div>
    </div>
</body>
</html>