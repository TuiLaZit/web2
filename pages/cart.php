<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../admincp/config/config.php');

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Giỏ hàng - NJZ gShop</title>
</head>
<body>
    <div class="wrapper">
        <div class="user-info">
            <h2>Thông tin người dùng</h2>
            <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['Account']); ?></p>
            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user['Name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user['PNumber']); ?></p>
            <a href="<?php echo $baseUrl ?>/pages/order-history.php" class="view-history-btn" style="display: inline-block; background: #4CAF50; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; margin-top: 10px;">Xem lịch sử mua hàng</a>
        </div>

        <div class="cart-container">
            <div class="cart-content">
                <div class="cart-main">
                    <h1>Giỏ hàng</h1>
                    <?php
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = array();
                    }

                    if (isset($_POST['increase_quantity'])) {
                        $product_id = $_POST['product_id'];
                        if (isset($_SESSION['cart'][$product_id])) {
                            $_SESSION['cart'][$product_id]++;
                        }
                    }
                    
                    if (isset($_POST['decrease_quantity'])) {
                        $product_id = $_POST['product_id'];
                        if (isset($_SESSION['cart'][$product_id])) {
                            $_SESSION['cart'][$product_id]--;
                            if ($_SESSION['cart'][$product_id] < 1) {
                                unset($_SESSION['cart'][$product_id]);
                            }
                        }
                    }
                    
                    if (isset($_POST['quantity']) && isset($_POST['product_id']) && isset($_POST['update_quantity'])) {
                        $product_id = $_POST['product_id'];
                        $quantity = (int)$_POST['quantity'];
                        if ($quantity > 0) {
                            $_SESSION['cart'][$product_id] = $quantity;
                        } else { 
                            unset($_SESSION['cart'][$product_id]);
                        }
                    }

                    if (isset($_POST['remove_item'])) {
                        $product_id = $_POST['product_id'];
                        unset($_SESSION['cart'][$product_id]);
                    }

                    // Hàm lấy thông tin sản phẩm từ CSDL dựa vào ID sản phẩm
                    function getProduct($product_id) {
                        global $mysqli; 
                        $sql = "SELECT * FROM sanpham WHERE IdSP = ? AND Status = 1"; 
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("i", $product_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            return $result->fetch_assoc();
                        }
                        $stmt->close();
                        return null;
                    }

                    $total = 0;

                    // Hàm định dạng số tiền sang chuỗi có dấu phân cách và đơn vị VND
                    function formatPrice($price) {
                        return number_format($price, 0, ',', '.') . ' VND';
                    }

                    // Hàm tạo chuỗi HTML cho một sản phẩm trong giỏ hàng
                    function renderCartItem($product, $quantity) {
                        $item_total = $product['Price'] * $quantity;
                        return '
                        <div class="cart-item">
                            <div class="product-info">
                                <img src="./admincp/img/products/' . htmlspecialchars($product['IMG']) . '" alt="' . htmlspecialchars($product['Name']) . '" class="product-image">
                                <div>
                                    <h3>' . htmlspecialchars($product['Name']) . '</h3>
                                    <p>' . htmlspecialchars($product['Type']) . '</p>
                                </div>
                            </div>
                            <div class="price">' . formatPrice($product['Price']) . '</div>
                            <div class="quantity-control">
                                <form method="POST" style="display: flex; align-items: center; gap: 10px;">
                                    <input type="hidden" name="product_id" value="' . $product['IdSP'] . '">
                                    <button type="submit" name="decrease_quantity" class="quantity-btn">-</button>
                                    <input type="number" name="quantity" value="' . $quantity . '" min="1" class="quantity-input" onblur="this.form.submit();" />
                                    <button type="submit" name="increase_quantity" class="quantity-btn">+</button>
                                </form>
                            </div>
                            <div class="total">' . formatPrice($item_total) . '</div>
                            <div class="action">
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="' . $product['IdSP'] . '">
                                    <button type="submit" name="remove_item" class="remove-btn">Xóa</button>
                                </form>
                            </div>
                        </div>';
                    }

                    // Hàm tạo chuỗi HTML cho thông báo khi giỏ hàng trống
                    function renderEmptyCart() {
                        return '
                        <div class="empty-cart">
                            <h2>Giỏ hàng của bạn đang trống</h2>
                            <p>Hãy thêm sản phẩm vào giỏ hàng để xem chúng ở đây.</p>
                            <a href="../index.php" class="checkout-btn">Tiếp tục mua sắm</a>
                        </div>';
                    }

                    // Hàm tạo chuỗi HTML cho phần tóm tắt tổng giá trị đơn hàng
                    function renderCartSummary($total) {
                        return '
                        <div class="cart-summary">
                            <h2>Tổng đơn hàng</h2>
                            <div class="summary-item">
                                <span>Tạm tính:</span>
                                <span>' . formatPrice($total) . '</span>
                            </div>
                            <div class="summary-item">
                                <span>Phí vận chuyển:</span>
                                <span>Miễn phí</span>
                            </div>
                            <div class="summary-item total">
                                <span>Tổng cộng:</span>
                                <span>' . formatPrice($total) . '</span>
                            </div>
                            <a href="./pages/checkout.php" class="checkout-btn">Thanh toán</a>
                        </div>';
                    }

                    if (empty($_SESSION['cart'])) {
                        echo renderEmptyCart();
                    } else {
                        echo '<div class="cart-header">
                                <div class="product">Sản phẩm</div>
                                <div class="price">Giá</div>
                                <div class="quantity">Số lượng</div>
                                <div class="total">Tổng</div>
                                <div class="action">Thao tác</div>
                              </div>';
                        
                        foreach ($_SESSION['cart'] as $product_id => $quantity) {
                            $product = getProduct($product_id);
                            if ($product) {
                                $item_total = $product['Price'] * $quantity;
                                $total += $item_total;
                                echo renderCartItem($product, $quantity);
                            } else {
                                unset($_SESSION['cart'][$product_id]);
                            }
                        }
                        
                        if ($total > 0) {
                           echo renderCartSummary($total);
                        } else {
                            echo renderEmptyCart();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
