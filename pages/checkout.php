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
$checkout_address = $_SESSION['checkout_address'] ?? '';
$checkout_payment = $_SESSION['checkout_payment'] ?? '';

$address_options = [];
if (!empty($user['AddressLine']) || !empty($user['Ward']) || !empty($user['District']) || !empty($user['Provinces'])) {
    $address_options[] = trim(($user['AddressLine'] ?? '') . ', ' . ($user['Ward'] ?? '') . ', ' . ($user['District'] ?? '') . ', ' . ($user['Provinces'] ?? ''));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['checkout_address']) && !empty($_POST['checkout_payment'])) {
    $_SESSION['checkout_address'] = $_POST['checkout_address'];
    $_SESSION['checkout_payment'] = $_POST['checkout_payment'];
}

$order_success = false;

if (isset($_POST['confirm_order'])) {
    $userId = $user['IdKH'];
    $total_calc = 0; 

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $total_calc = array_sum(array_map(function ($productId, $quantity) {
            global $mysqli;
            $product = getProduct($productId); 
            return $product ? $product['Price'] * $quantity : 0;
        }, array_keys($_SESSION['cart']), $_SESSION['cart']));
    }

    $addressType = $_POST['address_option'] ?? 'not_set';
    $addressLine = ''; $ward = ''; $district = ''; $province = '';

    if (strpos($addressType, 'saved_') === 0) { 
        $addressLine = $user['AddressLine'] ?? '';
        $ward = $user['Ward'] ?? '';
        $district = $user['District'] ?? '';
        $province = $user['Provinces'] ?? '';
    } elseif ($addressType === 'new') { 
        $addressLine = $_POST['address'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $district = $_POST['district'] ?? '';
        $province = $_POST['province'] ?? '';
    }

    $can_proceed = false;
    if (strpos($addressType, 'saved_') === 0) {
        $can_proceed = true; 
    } elseif ($addressType === 'new' && !empty($addressLine) && !empty($ward) && !empty($district) && !empty($province)) {
        $can_proceed = true; 
    }

    if ($can_proceed && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $stmt = $mysqli->prepare("INSERT INTO hoadon (IdKH, Total, Date, ExpectDate, Status, PTTT, AddressLine, Ward, Provinces, District) VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 1, ?, ?, ?, ?, ?)");
        if($stmt) {
            $paymentMethod = ($_POST['checkout_payment'] ?? 'cod') === 'cod' ? 1 : 2; 
            $stmt->bind_param("iiissss", $userId, $total_calc, $paymentMethod, $addressLine, $ward, $province, $district);

            if ($stmt->execute()) {
                $orderId = $stmt->insert_id; 
                $stmt->close();

                if ($orderId > 0) {
                    foreach ($_SESSION['cart'] as $productId => $quantity) {
                        $product = getProduct($productId);
                        if ($product) {
                            $price = $product['Price'];
                            $sumPrice = $price * $quantity;
                            $stmtItem = $mysqli->prepare("INSERT INTO chitiethoadon (IdHD, IdSP, Quantity, Price, SumPrice) VALUES (?, ?, ?, ?, ?)");
                            if($stmtItem){
                                $stmtItem->bind_param("iiiii", $orderId, $productId, $quantity, $price, $sumPrice);
                                $stmtItem->execute();
                                $stmtItem->close();
                            }

                            $updateQuantity = $mysqli->prepare("UPDATE sanpham SET Quantity = Quantity - ? WHERE IdSP = ?");
                            if($updateQuantity){
                                $updateQuantity->bind_param("ii", $quantity, $productId);
                                $updateQuantity->execute();
                                $updateQuantity->close();
                            }
                        }
                    }

                    $orderInfo = [
                        'id' => $orderId,
                        'name' => $user['Name'],
                        'email' => $user['Email'],
                        'phone' => $user['PNumber'],
                        'address_line' => $addressLine,
                        'ward' => $ward,
                        'district' => $district,
                        'province' => $province,
                        'payment' => $_POST['checkout_payment'],
                        'total' => $total_calc,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $orderItems = array_map(function ($productId, $quantity){
                        global $mysqli;
                        $product = getProduct($productId);
                        return $product ? [
                            'name' => $product['Name'],
                            'type' => $product['Type'],
                            'quantity' => $quantity,
                            'price' => $product['Price']
                        ] : null;
                    }, array_keys($_SESSION['cart']), $_SESSION['cart']);
                    $orderItems = array_filter($orderItems); 

                    $order_success = true; 
                    unset($_SESSION['cart'], $_SESSION['checkout_address'], $_SESSION['checkout_payment']);
                } else {
                     $order_success = false; 
                }
            } else {
                $order_success = false; 
                if ($stmt) $stmt->close();
            }
        } else {
            $order_success = false; 
        }
    } else {
        $order_success = false; 
    }
}

// Hàm lấy thông tin sản phẩm từ CSDL dựa trên ID sản phẩm
function getProduct($productId) {
    global $mysqli;
    if (!$mysqli || !($mysqli instanceof mysqli)) return null;

    $stmt = $mysqli->prepare("SELECT * FROM sanpham WHERE IdSP = ? AND Status = 1");
    if (!$stmt) return null;

    $stmt->bind_param("i", $productId);
    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }
    $result = $stmt->get_result();
    $productData = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $stmt->close();
    return $productData;
}

// Hàm định dạng số tiền sang chuỗi có dấu phân cách và đơn vị VND
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VND';
}
$total = 0; 
?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/cart.css">
<div class="wrapper">
    <div class="cart-container" style="max-width:700px;">
        <h1>Xác nhận đơn hàng</h1>
        <?php if ($order_success): ?>
            <div class="checkout-section" style="max-width:600px;margin:40px auto;">
                <h2>Hóa đơn của bạn</h2>
                <p><strong>Mã đơn hàng:</strong> <?php echo $orderInfo['id']; ?></p>
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($orderInfo['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($orderInfo['email']); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($orderInfo['phone']); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($orderInfo['address_line']) . ($orderInfo['ward'] ? ', ' . htmlspecialchars($orderInfo['ward']) : '') . ($orderInfo['district'] ? ', ' . htmlspecialchars($orderInfo['district']) : '') . ($orderInfo['province'] ? ', ' . htmlspecialchars($orderInfo['province']) : ''); ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?php echo ($orderInfo['payment'] ?? 'cod') === 'cod' ? 'Tiền mặt khi nhận hàng' : 'Thanh toán trực tuyến'; ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo $orderInfo['created_at']; ?></p>
                <h3>Chi tiết sản phẩm</h3>
                <ul>
                    <?php if(isset($orderItems) && is_array($orderItems)) foreach ($orderItems as $item): ?>
                        <li><?php echo htmlspecialchars($item['name']); ?> (<?php echo htmlspecialchars($item['type']); ?>) - x<?php echo $item['quantity']; ?> - <?php echo formatPrice($item['price']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Tổng cộng:</strong> <span style="color:#4CAF50;font-size:1.2em;"><?php echo formatPrice($orderInfo['total']); ?></span></p>
                <a href="../index.php" class="checkout-btn">Về trang chủ</a>
            </div>
        <?php else: ?>
            <?php if (isset($_POST['confirm_order']) && !$order_success): ?>
                <div class="checkout-section" style="max-width:600px;margin:40px auto;border: 1px solid red; padding: 15px; text-align:center;">
                    <h2 style="color:red;">Đặt hàng không thành công!</h2>
                    <p>Đã có lỗi xảy ra trong quá trình xử lý đơn hàng của bạn. Vui lòng kiểm tra lại thông tin, đặc biệt là địa chỉ giao hàng, hoặc thử lại sau.</p>
                    <a href="checkout.php" class="checkout-btn">Thử lại</a>
                </div>
            <?php endif; ?>

            <div class="checkout-wrapper" <?php if (isset($_POST['confirm_order']) && !$order_success) echo 'style="display:none;"'; ?> >
                <div class="checkout-left">
                    <div class="checkout-section">
                        <h2>Thông tin giao hàng</h2>
                        <form method="post" action="checkout.php" id="checkoutForm">
                            <div class="form-group">
                                <label for="name">Họ tên:</label>
                                <input type="text" id="name" value="<?php echo htmlspecialchars($user['Name']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" value="<?php echo htmlspecialchars($user['Email']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại:</label>
                                <input type="text" id="phone" value="<?php echo htmlspecialchars($user['PNumber']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="address_option">Chọn địa chỉ:</label>
                                <select name="address_option" id="address_option" onchange="toggleNewAddress(this.value)">
                                    <?php foreach ($address_options as $idx => $addr): ?>
                                        <option value="saved_<?php echo $idx; ?>"><?php echo htmlspecialchars($addr); ?></option>
                                    <?php endforeach; ?>
                                    <option value="new" <?php if(empty($address_options)) echo 'selected'; ?> >Nhập địa chỉ mới</option>
                                </select>
                            </div>
                            <?php foreach ($address_options as $idx => $addr): ?>
                                <div class="form-group saved_address" id="saved_address_<?php echo $idx; ?>" style="display:none;">
                                    <input type="text" name="saved_address_input_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($addr); ?>" readonly>
                                </div>
                            <?php endforeach; ?>
                             <div class="form-group" id="new_address" style="display:none;">
                                <select name="province" id="province-input"><option value="">Tỉnh / Thành phố (*)</option></select><p id="province-alert" class="alert" style="color: red;"></p>
                                <select name="district" id="district-input" disabled><option value="">Quận / Huyện (*)</option></select><p id="district-alert" class="alert" style="color: red;"></p>
                                <select name="ward" id="ward-input" disabled><option value="">Xã / Phường / Thị trấn (*)</option></select><p id="ward-alert" class="alert" style="color: red;"></p>
                                <input name="address" type="text" id="address-input-field" placeholder="Số nhà, tên đường (*)" disabled /><p id="address-alert" class="alert" style="color: red;"></p> 
                            </div>
                            <input type="hidden" name="checkout_address" id="checkout_address_hidden_input" value=""> 
                            <div class="form-group">
                                <label>Phương thức thanh toán:</label>
                                <div class="payment-methods">
                                    <label><input type="radio" name="checkout_payment" value="cod" <?php echo (!isset($_POST['checkout_payment']) || $_POST['checkout_payment'] === 'cod') ? 'checked' : ''; ?> > Tiền mặt khi nhận hàng</label>
                                    <label><input type="radio" name="checkout_payment" value="online" <?php echo (isset($_POST['checkout_payment']) && $_POST['checkout_payment'] === 'online') ? 'checked' : ''; ?> > Thanh toán trực tuyến</label>
                                </div>
                            </div>
                            <button type="submit" name="confirm_order" class="checkout-btn">Xác nhận đặt hàng</button>
                        </form>
                    </div>
                </div>
                <div class="checkout-right">
                    <div class="checkout-section">
                        <h2>Đơn hàng của bạn</h2>
                        <?php
                        $display_total = 0; 
                        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $productId => $quantity):
                                $product = getProduct($productId);
                                if ($product):
                                    $itemTotal = $product['Price'] * $quantity;
                                    $display_total += $itemTotal;
                                ?>
                                <div class="order-item">
                                    <img src="../admincp/img/products/<?php echo htmlspecialchars($product['IMG']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
                                    <div>
                                        <div class="order-name"><?php echo htmlspecialchars($product['Name']); ?></div>
                                        <div class="order-type"><?php echo htmlspecialchars($product['Type']); ?></div>
                                        <div class="order-qty">x<?php echo $quantity; ?></div>
                                    </div>
                                    <div class="order-price"><?php echo formatPrice($itemTotal); ?></div>
                                </div>
                            <?php endif; endforeach;
                        } else {
                            echo "<p>Giỏ hàng của bạn đang trống.</p>";
                        }?>
                        <div class="order-summary">
                            <div class="summary-row"><span>Tạm tính:</span><span><?php echo formatPrice($display_total); ?></span></div>
                            <div class="summary-row"><span>Phí vận chuyển:</span><span>Miễn phí</span></div>
                            <div class="summary-row total"><span>Tổng cộng:</span><span><?php echo formatPrice($display_total); ?></span></div>
                        </div>
                         <div id="bank-info" style="display: none; margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                            <h3>Thông tin chuyển khoản</h3>
                            <p><strong>Ngân hàng:</strong> Vietcombank</p>
                            <p><strong>Chủ tài khoản:</strong> CÔNG TY TNHH NJZ GSHOP</p>
                            <p><strong>Số tài khoản:</strong> 1234567890</p>
                            <p><strong>Chi nhánh:</strong> Hà Nội</p>
                            <p><strong>Nội dung chuyển khoản:</strong> [Mã đơn hàng] - [Tên khách hàng]</p>
                            <p style="color: #dc3545; font-style: italic;">* Vui lòng chuyển khoản đúng số tiền và nội dung để đơn hàng được xử lý nhanh chóng.</p>
                            
                            <button type="button" id="confirm-payment-button" style="margin-top: 15px; padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Đã thanh toán</button>
                            <p id="payment-status-message" style="margin-top: 10px; font-weight: bold;"></p>
                            </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="../admincp/js/vietnamese-provinces-data.js"></script> 
<script>
    window.checkoutPageConfig = {
        addressOptionsCount: <?php echo count($address_options); ?>
    };
</script>
<script src="../js/checkout.js"></script> 
