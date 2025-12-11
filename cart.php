<?php
session_start();
include 'config.php';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. Xử lý THÊM vào giỏ (Action: add)
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $id = intval($_GET['id']);
    // Nếu sản phẩm đã có trong giỏ, tăng số lượng lên 1, chưa có thì gán bằng 1
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php"); // Chuyển hướng về trang giỏ hàng
    exit();
}

// 2. Xử lý XÓA khỏi giỏ (Action: delete)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// 3. Xử lý THANH TOÁN (Làm sạch giỏ)
if (isset($_POST['checkout'])) {
    // Ở đây bạn sẽ thêm code lưu đơn hàng vào database (Bảng Orders)
    // Trong bài này ta chỉ giả lập thành công
    unset($_SESSION['cart']);
    $success_msg = "Đặt hàng thành công! Cảm ơn bạn đã mua sách tại Shinebooks.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng của bạn</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo"><h2>Shinebooks Cart</h2></div>
        <div><a href="index.php">Tiếp tục mua sắm</a></div>
    </nav>

    <div class="container">
        <?php if(isset($success_msg)): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px;">
                <?php echo $success_msg; ?>
            </div>
            <a href="index.php" class="btn">Về trang chủ</a>
        <?php else: ?>
        
        <h2>Giỏ hàng của bạn</h2>
        
        <?php if(empty($_SESSION['cart'])): ?>
            <p>Giỏ hàng đang trống. <a href="index.php">Mua sách ngay!</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_bill = 0;
                    // Lặp qua session cart để lấy ID và Số lượng
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $sql = "SELECT * FROM products WHERE id = $product_id";
                        $result = mysqli_query($conn, $sql);
                        if($row = mysqli_fetch_assoc($result)) {
                            $subtotal = $row['price'] * $quantity;
                            $total_bill += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="images/<?php echo $row['image']; ?>" width="50" style="vertical-align: middle; margin-right: 10px;">
                            <?php echo $row['title']; ?>
                        </td>
                        <td><?php echo number_format($row['price']); ?>đ</td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo number_format($subtotal); ?>đ</td>
                        <td><a href="cart.php?action=delete&id=<?php echo $product_id; ?>" style="color: red;">Xóa</a></td>
                    </tr>
                    <?php 
                        } // end if
                    } // end foreach
                    ?>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
                        <td colspan="2" style="font-weight: bold; color: red; font-size: 18px;">
                            <?php echo number_format($total_bill); ?>đ
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div style="text-align: right; margin-top: 20px;">
                <form method="POST">
                    <button type="submit" name="checkout" class="btn btn-warning">Thanh Toán Ngay</button>
                </form>
            </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>