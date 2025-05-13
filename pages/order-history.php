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

// Lấy danh sách đơn hàng của người dùng
$stmt = $mysqli->prepare("
    SELECT h.*, 
           COUNT(ct.IdSP) as total_items,
           GROUP_CONCAT(CONCAT(sp.Name, ' (', ct.Quantity, ')') SEPARATOR ', ') as items
    FROM hoadon h
    LEFT JOIN chitiethoadon ct ON h.IdHD = ct.IdHD
    LEFT JOIN sanpham sp ON ct.IdSP = sp.IdSP
    WHERE h.IdKH = ?
    GROUP BY h.IdHD
    ORDER BY h.Date DESC
");
$stmt->bind_param("i", $user['IdKH']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
    <title>Lịch sử mua hàng - NJZ gShop</title>
    <style>
        .order-history {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .order-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .order-info p {
            margin: 5px 0;
        }
        .order-items {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }
        .order-total {
            text-align: right;
            font-size: 1.2em;
            color: #4CAF50;
            margin-top: 15px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status-1 { background: #fff3cd; color: #856404; }
        .status-2 { background: #cce5ff; color: #004085; }
        .status-3 { background: #d4edda; color: #155724; }
        .status-4 { background: #d1e7dd; color: #0f5132; }
        .status-5 { background: #f8d7da; color: #721c24; }
        .view-details-btn {
            background: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .view-details-btn:hover {
            background: #45a049;
        }
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
        <div class="order-history">
            <a href="cart.php" class="back-btn">← Quay lại</a>
            <h1>Lịch sử mua hàng</h1>
            <?php if (empty($orders)): ?>
                <p>Bạn chưa có đơn hàng nào.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <h3>Đơn hàng #<?php echo $order['IdHD']; ?></h3>
                            <span class="status-badge status-<?php echo $order['Status']; ?>">
                                <?php echo getStatusText($order['Status']); ?>
                            </span>
                        </div>
                        <div class="order-info">
                            <div>
                                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></p>
                                <p><strong>Dự kiến giao:</strong> <?php echo date('d/m/Y', strtotime($order['ExpectDate'])); ?></p>
                            </div>
                            <div>
                                <p><strong>Phương thức thanh toán:</strong> <?php echo getPaymentMethod($order['PTTT']); ?></p>
                                <p><strong>Số sản phẩm:</strong> <?php echo $order['total_items']; ?></p>
                            </div>
                            <div>
                                <p><strong>Địa chỉ giao hàng:</strong></p>
                                <p><?php 
                                    echo htmlspecialchars($order['AddressLine']);
                                    if ($order['Ward']) echo ', ' . htmlspecialchars($order['Ward']);
                                    if ($order['District']) echo ', ' . htmlspecialchars($order['District']);
                                    if ($order['Provinces']) echo ', ' . htmlspecialchars($order['Provinces']);
                                ?></p>
                            </div>
                        </div>
                        <div class="order-items">
                            <p><strong>Sản phẩm:</strong> <?php echo htmlspecialchars($order['items']); ?></p>
                        </div>
                        <div class="order-total">
                            <p><strong>Tổng tiền:</strong> <?php echo formatPrice($order['Total']); ?></p>
                        </div>
                        <div style="text-align: right; margin-top: 15px;">
                            <a href="order-details.php?id=<?php echo $order['IdHD']; ?>" class="view-details-btn">Xem chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 
