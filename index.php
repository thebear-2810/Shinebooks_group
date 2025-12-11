<?php
session_start();
include 'config.php';

// --- PHẦN MỚI THÊM: Tính tổng số lượng sách trong giỏ hàng ---
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
// -------------------------------------------------------------

$search = "";
$sql = "SELECT * FROM products";

// Xử lý tìm kiếm
if (isset($_GET['keyword'])) {
    $search = $_GET['keyword'];
    $sql .= " WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
}

// Lấy danh sách sản phẩm (sắp xếp mới nhất lên đầu)
$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shinebooks - Trang chủ</title>
    <link rel="stylesheet" href="style.css">
    <meta charset="utf-8">
</head>
<body>
    <nav>
        <div class="logo"><h2>Shinebooks</h2></div>
        
        <div style="display: flex; align-items: center;">
            <form action="" method="GET" style="margin-right: 15px;">
                <input type="text" name="keyword" placeholder="Tìm sách..." value="<?php echo $search; ?>" style="width: 200px; padding: 5px;">
                <button type="submit" class="btn" style="padding: 6px 10px; margin-top:0;">Tìm</button>
            </form>

            <a href="cart.php" style="background: #fff; color: #333; padding: 6px 12px; border-radius: 4px; font-weight: bold; margin-right: 15px; text-decoration: none;">
                🛒 Giỏ hàng (<?php echo $cart_count; ?>)
            </a>

            <?php if(isset($_SESSION['user'])): ?>
                <span style="margin-right: 10px;">Chào, <b><?php echo $_SESSION['user']; ?></b></span>
                
                <?php if($_SESSION['role'] == 'admin'): ?> 
                    <a href="admin.php" style="background: #ffc107; color: #000; padding: 5px 10px; border-radius: 4px;">Quản trị</a> 
                <?php endif; ?>
                
                <a href="login.php?logout=true">Đăng xuất</a>
            <?php else: ?>
                <a href="login.php">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h3 style="border-bottom: 2px solid #ddd; padding-bottom: 10px;">
            <?php echo ($search != "") ? "Kết quả tìm kiếm cho: '$search'" : "Sách Mới Nổi Bật"; ?>
        </h3>

        <div class="product-grid">
            <?php 
            if(mysqli_num_rows($result) > 0):
                while($row = mysqli_fetch_assoc($result)): 
            ?>
            <div class="product-card">
                <?php if($row['discount_percent'] > 0): ?>
                    <span class="badge">-<?php echo $row['discount_percent']; ?>%</span>
                <?php endif; ?>
                
                <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                </a>

                <div class="title">
                    <a href="product_detail.php?id=<?php echo $row['id']; ?>" style="color: #333; text-decoration: none;">
                        <?php echo $row['title']; ?>
                    </a>
                </div>
                
                <div class="author" style="font-size: 0.9em; color: #666;"><?php echo $row['author']; ?></div>
                
                <div style="margin-top: 5px;">
                    <span class="price"><?php echo number_format($row['price']); ?>đ</span>
                    <?php if($row['old_price'] > 0): ?>
                        <span class="old-price"><?php echo number_format($row['old_price']); ?>đ</span>
                    <?php endif; ?>
                </div>

                <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn">Xem chi tiết</a>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
                <p>Không tìm thấy cuốn sách nào!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>