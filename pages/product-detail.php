<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    include("../admincp/config/config.php");
    include("../class/product_page.php");
    
    // Handle add to cart
    if(isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Initialize cart if not exists
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Add or update quantity in cart
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        // Redirect to cart page
        header('Location: ../index.php?quanly=giohang');
        exit();
    }

    if(isset($_GET['id'])){
        $id=$_GET['id'];
        $sanpham = productPage::getProductById($id);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/product-detail-style.css">
    <title><?php echo isset($sanpham) ? htmlspecialchars($sanpham->name) . " - NJZ gShop" : "NJZ gShop"; ?></title>
</head>
<body>
    <div class="wrapper">
        <?php include("header.php"); ?>
        <?php
        if($sanpham){
        ?>
            <div class="product-detail">
                <div class="prod-img">
                    <img src="./../admincp/img/products/<?php echo htmlspecialchars($sanpham->image); ?>" alt="<?php echo htmlspecialchars($sanpham->name); ?>">
                </div>
                <div class="prod-info">
                    <h1><?php echo htmlspecialchars($sanpham->name); ?></h1>
                    <p>Giá: <?php echo htmlspecialchars(number_format($sanpham->price, 0, ',', '.')); ?> VND</p>
                    <p>Loại: <?php echo htmlspecialchars($sanpham->type); ?></p>
                    <p>Nhóm sản phẩm: <?php echo htmlspecialchars($sanpham->grpname); ?></p>
                    <p>Số lượng: <?php echo htmlspecialchars($sanpham->quant); ?></p>
                    <p>Thông tin: <?php echo htmlspecialchars($sanpham->info); ?></p>
                    <p>Ngày phát hành: <?php echo htmlspecialchars($sanpham->releaseDate); ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($sanpham->id); ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($sanpham->quant); ?>" style="width: 60px; margin-right: 10px;">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Thêm vào giỏ hàng</button>
                    </form>
                </div>
            </div>
        <?php
            } else {
                echo "<p>Sản phẩm không tồn tại!</p>";
            } 
            }else {
                echo "<p>Không tìm thấy ID sản phẩm!</p>";
            }
            include("footer.php");
        ?>
    </div>
</body> 

</html>
