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
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin đơn hàng
$stmt = $mysqli->prepare("
    SELECT h.*, 
           kh.Name as customer_name,
           kh.Email as customer_email,
           kh.PNumber as customer_phone
    FROM hoadon h
    JOIN khachhang kh ON h.IdKH = kh.IdKH
    WHERE h.IdHD = ? AND h.IdKH = ?
");
$stmt->bind_param("ii", $order_id, $user['IdKH']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: order-history.php');
    exit();
}

// Lấy chi tiết sản phẩm trong đơn hàng
$stmt = $mysqli->prepare("
    SELECT ct.*, sp.Name, sp.Type, sp.IMG
    FROM chitiethoadon ct
    JOIN sanpham sp ON ct.IdSP = sp.IdSP
    WHERE ct.IdHD = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VND';
}

function getStatusText($status) {
    switch ($status) {
        case 1:
            return 'Chờ xác nhận';
        case 2:
            return 'Đã xác nhận';
        case 3:
            return 'Đang giao hàng';
        case 4:
            return 'Đã giao hàng';
        case 5:
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}

function getPaymentMethod($method) {
    return $method == 1 ? 'Tiền mặt khi nhận hàng' : 'Thanh toán trực tuyến';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Chi tiết đơn hàng #<?php echo $order_id; ?> - NJZ gShop</title>
    <style>
        .order-details {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        .order-header {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }
        .info-section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .order-items {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }
        .item-info {
            flex-grow: 1;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .item-type {
            color: #666;
            font-size: 0.9em;
        }
        .item-price {
            text-align: right;
            min-width: 150px;
        }
        .order-summary {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2em;
            color: #4CAF50;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
            display: inline-block;
        }
        .status-1 { background: #fff3cd; color: #856404; }
        .status-2 { background: #cce5ff; color: #004085; }
        .status-3 { background: #d4edda; color: #155724; }
        .status-4 { background: #d1e7dd; color: #0f5132; }
        .status-5 { background: #f8d7da; color: #721c24; }
        .back-btn {
            background: #666;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="order-details">
            <a href="order-history.php" class="back-btn">← Quay lại</a>
            
            <div class="order-header">
                <h1>Chi tiết đơn hàng #<?php echo $order_id; ?></h1>
                <span class="status-badge status-<?php echo $order['Status']; ?>">
                    <?php echo getStatusText($order['Status']); ?>
                </span>
            </div>

            <div class="order-info">
                <div class="info-section">
                    <h3>Thông tin khách hàng</h3>
                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                </div>

                <div class="info-section">
                    <h3>Thông tin giao hàng</h3>
                    <p><strong>Địa chỉ:</strong><br>
                        <?php 
                            echo htmlspecialchars($order['AddressLine']);
                            if ($order['Ward']) echo ', ' . htmlspecialchars($order['Ward']);
                            if ($order['District']) echo ', ' . htmlspecialchars($order['District']);
                            if ($order['Provinces']) echo ', ' . htmlspecialchars($order['Provinces']);
                        ?>
                    </p>
                    <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></p>
                    <p><strong>Dự kiến giao:</strong> <?php echo date('d/m/Y', strtotime($order['ExpectDate'])); ?></p>
                </div>

                <div class="info-section">
                    <h3>Thông tin thanh toán</h3>
                    <p><strong>Phương thức:</strong> <?php echo getPaymentMethod($order['PTTT']); ?></p>
                    <p><strong>Trạng thái:</strong> <?php echo getStatusText($order['Status']); ?></p>
                </div>
            </div>

            <div class="order-items">
                <h2>Sản phẩm đã đặt</h2>
                <?php foreach ($order_items as $item): ?>
                    <div class="item">
                        <img src="../admincp/img/products/<?php echo $item['IMG']; ?>" alt="<?php echo htmlspecialchars($item['Name']); ?>">
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($item['Name']); ?></div>
                            <div class="item-type"><?php echo htmlspecialchars($item['Type']); ?></div>
                            <div>Số lượng: <?php echo $item['Quantity']; ?></div>
                        </div>
                        <div class="item-price">
                            <div><?php echo formatPrice($item['Price']); ?></div>
                            <div style="color: #4CAF50;"><?php echo formatPrice($item['SumPrice']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span><?php echo formatPrice($order['Total']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span>Miễn phí</span>
                </div>
                <div class="summary-row">
                    <span>Tổng cộng:</span>
                    <span><?php echo formatPrice($order['Total']); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 