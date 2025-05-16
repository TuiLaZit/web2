<?php
require_once(__DIR__ . '/../../config/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = intval($_POST['new_status']);

    $sql_get_current_status = "SELECT Status FROM hoadon WHERE IdHD = " . intval($orderId);
    $result_current_status = $mysqli->query($sql_get_current_status);

    if ($result_current_status && $result_current_status->num_rows > 0) {
        $row_current_status = $result_current_status->fetch_assoc();
        $currentStatus = intval($row_current_status['Status']);

        if ($newStatus > $currentStatus) {
            $sql_update = "UPDATE hoadon SET Status = " . $newStatus . " WHERE IdHD = " . intval($orderId);
            if ($mysqli->query($sql_update)) {
                $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng #" . $orderId . " thành công.";
            } else {
                $_SESSION['error_message'] = "Lỗi khi cập nhật trạng thái đơn hàng #" . $orderId . ": " . $mysqli->error;
            }
        } else {
            $_SESSION['error_message'] = "Không thể cập nhật trạng thái đơn hàng #" . $orderId . " về trạng thái trước đó hoặc hiện tại.";
        }
    } else {
        $_SESSION['error_message'] = "Không tìm thấy đơn hàng #" . $orderId . ".";
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$searchTerm = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';
$locationFilter = isset($_GET['location']) ? trim($_GET['location']) : '';

// Các biến này vẫn được dùng để lọc SQL và truyền cho JavaScript
$provinceFilter = isset($_GET['province']) ? $_GET['province'] : '';
$districtFilter = isset($_GET['district']) ? $_GET['district'] : '';
$wardFilter = isset($_GET['ward']) ? $_GET['ward'] : '';

$sql = "SELECT h.IdHD, h.IdKH, h.Total, h.Date, h.ExpectDate, h.Status, h.PTTT,
        k.Name as CustomerName,
        k.Email,
        k.PNumber,
        h.AddressLine,
        h.Ward,
        h.District,
        h.Provinces
FROM hoadon h
JOIN khachhang k ON h.IdKH = k.IdKH
WHERE 1";

$sql .= " AND h.Date < h.ExpectDate";

if (!empty($statusFilter)) {
    $sql .= " AND h.Status = " . intval($statusFilter);
}

if (!empty($fromDate) && !empty($toDate)) {
    $sql .= " AND h.Date BETWEEN '" . $mysqli->real_escape_string($fromDate) . "' AND '" . $mysqli->real_escape_string($toDate) . "'";
}

if (!empty($searchTerm)) {
    $escapedSearchTerm = $mysqli->real_escape_string($searchTerm);
    $sql .= " AND (k.Name LIKE '%" . $escapedSearchTerm . "%' 
                OR k.PNumber LIKE '%" . $escapedSearchTerm . "%'
                OR k.Email LIKE '%" . $escapedSearchTerm . "%')";
}

if (!empty($locationFilter)) {
    $escapedLocation = $mysqli->real_escape_string($locationFilter);
    $sql .= " AND (h.AddressLine LIKE '%" . $escapedLocation . "%' 
              OR h.Ward LIKE '%" . $escapedLocation . "%' 
              OR h.District LIKE '%" . $escapedLocation . "%' 
              OR h.Provinces LIKE '%" . $escapedLocation . "%')";
}

if (!empty($provinceFilter)) {
    $escapedProvince = $mysqli->real_escape_string($provinceFilter);
    $sql .= " AND h.Provinces = '" . $escapedProvince . "'";
}

if (!empty($districtFilter)) {
    $escapedDistrict = $mysqli->real_escape_string($districtFilter);
    $sql .= " AND h.District = '" . $escapedDistrict . "'";
}

if (!empty($wardFilter)) {
    $escapedWard = $mysqli->real_escape_string($wardFilter);
    $sql .= " AND h.Ward = '" . $escapedWard . "'";
}

$sql .= " ORDER BY h.Date DESC";    
$result = $mysqli->query($sql);
?>
<div style="position: relative; width: 100%; height: calc(100vh - 54px);">
<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
    <div style="height: 100vh;padding-left:1em; width: 100%; overflow-y: auto; position: relative;">
<div id="toast-notification-container" >
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="toast-notification toast-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Thành công!</strong>
                <button type="button" class="btn-close-toast" aria-label="Close">&times;</button>
            </div>
            <div class="toast-body">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="toast-notification toast-error" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Lỗi!</strong>
                <button type="button" class="btn-close-toast" aria-label="Close">&times;</button>
            </div>
            <div class="toast-body">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="container-fluid" >
    <h2 class="mb-4">Quản lý đơn hàng</h2>

    <form method="GET" action="" class="mb-3 row gx-3 gy-2 align-items-center filter-form">
        <?php
        $currentAction = htmlspecialchars(isset($_GET['action']) ? $_GET['action'] : 'orders'); 
        ?>
        <input type="hidden" name="action" value="<?php echo $currentAction; ?>">

        <div class="col-sm-auto">
            <label class="visually-hidden" for="search_term">Tìm kiếm (Tên, SĐT, Email):</label>
            <input type="text" class="form-control" name="search_term" id="search_term" placeholder="Thông tin Khách Hàng" value="<?php echo htmlspecialchars($searchTerm); ?>">
        </div>
        <div class="col-sm-auto">
            <label class="visually-hidden" for="status">Trạng thái đơn:</label>
            <select class="form-select" name="status" id="status">
                <option value="">Tất cả Trạng thái</option>
                <option value="1" <?php if ($statusFilter == '1') echo 'selected'; ?>>Chưa xác nhận</option>
                <option value="2" <?php if ($statusFilter == '2') echo 'selected'; ?>>Đã xác nhận</option>
                <option value="3" <?php if ($statusFilter == '3') echo 'selected'; ?>>Đã giao</option>
                <option value="4" <?php if ($statusFilter == '4') echo 'selected'; ?>>Đã hủy</option>
            </select>
        </div>

        <div class="col-sm-auto">
            <label class="visually-hidden" for="from_date">Từ ngày:</label>
            <input type="date" class="form-control" name="from_date" id="from_date" value="<?php echo $fromDate; ?>">
        </div>
        <div class="col-sm-auto">
            <label class="visually-hidden" for="to_date">Đến ngày:</label>
            <input type="date" class="form-control" name="to_date" id="to_date" value="<?php echo $toDate; ?>">
        </div>
        
        <div class="col-sm-auto">
            <label class="visually-hidden" for="province">Tỉnh/Thành phố:</label>
            <select class="form-select" name="province" id="province">
                <option value="">Tất cả Tỉnh/TP</option>
                </select>
        </div>
        <div class="col-sm-auto">
            <label class="visually-hidden" for="district">Quận/Huyện:</label>
            <select class="form-select" name="district" id="district" disabled>
                <option value="">Tất cả Quận/Huyện</option>
                </select>
        </div>
        <div class="col-sm-auto">
            <label class="visually-hidden" for="ward">Xã/Phường/Thị trấn:</label>
            <select class="form-select" name="ward" id="ward" disabled>
                <option value="">Tất cả Xã/Phường</option>
                </select>
        </div>

        <div class="col-sm-auto">
            <label class="visually-hidden" for="location">Địa điểm:</label>
            <input type="text" class="form-control" name="location" id="location" placeholder="Địa chỉ cụ thể/chung" value="<?php echo htmlspecialchars($locationFilter); ?>">
        </div>
        
        <div class="col-sm-auto">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Ngày giao dự kiến</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['IdHD']; ?></td>
                            <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['PNumber']); ?></td>
                            <td>
                                <?php
                                echo htmlspecialchars($row['AddressLine'] . ', ' . $row['Ward'] . ', ' .
                                                    $row['District'] . ', ' . $row['Provinces']);
                                ?>
                            </td>
                            <td><?php echo number_format($row['Total'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo date('d/m/Y', strtotime($row['Date'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['ExpectDate'])); ?></td>
                            <td>
                                <form method="post" style="margin: 0;" class="form-update-status"> <input type="hidden" name="order_id" value="<?php echo $row['IdHD']; ?>">
                                    <select name="new_status" 
                                            class="form-select form-select-sm" 
                                            data-current-status="<?php echo $row['Status']; ?>"
                                            onchange="confirmOrderStatusUpdate(this, '<?php echo $row['IdHD']; ?>', this.options[this.selectedIndex].text)">
                                        <option value="1" <?php if ($row['Status'] == 1) echo 'selected'; ?>>Chưa xác nhận</option>
                                        <option value="2" <?php if ($row['Status'] == 2) echo 'selected'; ?>>Đã xác nhận</option>
                                        <option value="3" <?php if ($row['Status'] == 3) echo 'selected'; ?>>Đã giao</option>
                                        <option value="4" <?php if ($row['Status'] == 4) echo 'selected'; ?>>Đã hủy</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="true">
                                </form>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?action=orders&query=details&id=<?php echo $row['IdHD']; ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Không tìm thấy đơn hàng nào phù hợp.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
<link rel="stylesheet" type="text/css" href="./css/quan-ly-hoa-don.css?v=<?php echo time(); ?>">

<script>
  const currentSelectedProvince = "<?php echo htmlspecialchars($provinceFilter, ENT_QUOTES, 'UTF-8'); ?>";
  const currentSelectedDistrict = "<?php echo htmlspecialchars($districtFilter, ENT_QUOTES, 'UTF-8'); ?>";
  const currentSelectedWard = "<?php echo htmlspecialchars($wardFilter, ENT_QUOTES, 'UTF-8'); ?>";
</script>

<script src="../admincp/js/vietnamese-provinces-data.js?v=<?php echo time(); ?>"></script> 
<script src="./js/quan-ly-don-hang.js?v=<?php echo time(); ?>"></script>
