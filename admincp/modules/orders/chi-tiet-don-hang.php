<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once(__DIR__ . '/../../config/config.php');

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header('Location: ?action=orders');
    exit();
}

$sql = "SELECT h.*, k.Name as CustomerName, k.PNumber as CustomerPhone,
                CONCAT(k.AddressLine, ', ', k.Ward, ', ', k.District, ', ', k.Provinces) as CustomerAddress
        FROM hoadon h
        JOIN khachhang k ON h.IdKH = k.IdKH
        WHERE h.IdHD = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header('Location: ?action=orders');
    exit();
}

$items_sql = "SELECT c.*, p.Name as ProductName, p.Price as ProductPrice
              FROM chitiethoadon c
              JOIN sanpham p ON c.IdSP = p.IdSP
              WHERE c.IdHD = ?";
$items_stmt = $mysqli->prepare($items_sql);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
?>

<div class="container-fluid">
    <h2 class="mb-4">Chi tiết đơn hàng #<?php echo $order_id; ?></h2>
    
    <div class="info-container">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Thông tin đơn hàng</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> #<?php echo $order_id; ?></p>
                        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y', strtotime($order['Date'])); ?></p>
                        <p><strong>Trạng thái:</strong>
                            <?php
                            $status_text = '';
                            switch($order['Status']) {
                                case 1:
                                    $status_text = 'Chưa xác nhận';
                                    break;
                                case 2:
                                    $status_text = 'Đã xác nhận';
                                    break;
                                case 3:
                                    $status_text = 'Đã giao';
                                    break;
                                case 4:
                                    $status_text = 'Đã hủy';
                                    break;
                            }
                            echo $status_text;
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tổng tiền:</strong> <?php echo number_format($order['Total'], 0, ',', '.'); ?> VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Thông tin khách hàng</h5>
                <p><strong>Tên khách hàng:</strong> <?php echo htmlspecialchars($order['CustomerName']); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['CustomerPhone']); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['CustomerAddress']); ?></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Chi tiết sản phẩm</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['ProductName']); ?></td>
                            <td><?php echo number_format($item['ProductPrice'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo $item['Quantity']; ?></td>
                            <td><?php echo number_format($item['ProductPrice'] * $item['Quantity'], 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="?action=orders" class="btn btn-secondary">Quay lại</a>
        <?php if ($order['Status'] != 3 && $order['Status'] != 4): ?>
        <a href="?action=orders&query=update&id=<?php echo $order_id; ?>" class="btn btn-primary">Cập nhật trạng thái</a>
        <?php endif; ?>
    </div>
</div> 
<style>
.container-fluid {
    padding: 12px;
}

h2.mb-4 {
    font-size: 1.6rem; 
    margin-bottom: 1rem;
    color: #222; 
    font-weight: 700; 
    border-bottom: 2px solid #ddd;
    padding-bottom: 0.5rem;
}

.info-container {
    display: flex;
    gap: 1rem; 
    margin-bottom: 1rem; 
}

.info-container .card {
    flex: 1; 
    margin-bottom: 0; 
}

.card {
    margin-bottom: 1rem;
    border: 1px solid #ccc; 
    border-radius: 0.2rem;
    box-shadow: 0 0.08rem 0.15rem rgba(0, 0, 0, 0.1); 
}

.card-body {
    padding: 1rem;
}

.card-title {
    font-size: 1.2rem; 
    margin-bottom: 0.7rem;
    color: #333; 
    font-weight: 600; 
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.4rem;
    margin-top: 0;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -10px;
    margin-left: -10px;
    margin-bottom: 0.5rem;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 10px;
    padding-left: 10px;
}

p {
    margin-bottom: 0.4rem;
    color: #444; 
    font-size: 0.95rem; 
    line-height: 1.6; 
}

strong {
    font-weight: 700; 
    color: #222;
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border: 1px solid #ccc; 
    border-radius: 0.2rem;
    margin-bottom: 1rem;
}

.table {
    width: 100%;
    margin-bottom: 0;
    color: #333; 
    border-collapse: collapse;
    font-size: 0.9rem; 
}

.table thead th {
    padding: 0.6rem;
    vertical-align: bottom;
    border-bottom: 2px solid #ccc; 
    font-weight: 700; 
    color: #222;
    text-align: left;
    background-color:rgb(89, 229, 234); 
}

.table tbody td {
    padding: 0.5rem;
    vertical-align: top;
    border-top: 1px solid #eee;
}

.table thead th:nth-child(1),
.table tbody td:nth-child(1) {
    width: 50%; 
    text-align: center;
}

.table thead th:nth-child(2),
.table tbody td:nth-child(2) {
    width: 15%; 
    text-align: center;
}

.table thead th:nth-child(3),
.table tbody td:nth-child(3) {
    width: 15%; 
    text-align: center;
}

.table thead th:nth-child(4),
.table tbody td:nth-child(4) {
    width: 20%; 
    text-align: center;
}

.mt-3 {
    margin-top: 0.8rem;
}

.btn-group {
    display: flex;
    gap: 0.6rem;
}

.btn {
    display: inline-block;
    font-weight: 600; 
    color: #fff;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.35rem 0.7rem;
    font-size: 0.9rem; 
    line-height: 1.5;
    border-radius: 0.2rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-secondary {
    color: #fff;
    background-color: #5a6268; 
    border-color: #5a6268;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #495057;
    border-color: #495057;
}

.btn-primary {
    color: #fff;
    background-color: #0056b3; 
    border-color: #0056b3;
}

.btn-primary:hover {
    color: #fff;
    background-color: #004494;
    border-color: #004494;
}
</style>
