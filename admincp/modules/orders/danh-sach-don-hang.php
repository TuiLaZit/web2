<?php
require_once(__DIR__ . '/../../config/config.php'); // Đảm bảo đường dẫn này chính xác

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = intval($_POST['new_status']);

    $stmt_get_current = $mysqli->prepare("SELECT Status FROM hoadon WHERE IdHD = ?");
    if (!$stmt_get_current) {
        $_SESSION['error_message'] = "Lỗi hệ thống: Không thể chuẩn bị truy vấn. Vui lòng thử lại sau.";
        error_log("Prepare failed (get_current_status): " . $mysqli->error);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
    $stmt_get_current->bind_param("i", $orderId);
    $stmt_get_current->execute();
    $result_current_status = $stmt_get_current->get_result();

    if ($result_current_status && $result_current_status->num_rows > 0) {
        $row_current_status = $result_current_status->fetch_assoc();
        $currentStatus = intval($row_current_status['Status']);

        // Kiểm tra điều kiện mới: Không cho phép hủy đơn đã giao
        if ($currentStatus == 3 && $newStatus == 4) { // Status 3: Đã giao, Status 4: Đã hủy
            $_SESSION['error_message'] = "Đơn hàng #" . $orderId . " đã được giao, không thể cập nhật thành Đã Hủy.";
        } elseif ($newStatus > $currentStatus) { // Chỉ cho phép cập nhật nếu trạng thái mới "tiến triển" hơn trạng thái hiện tại
            $mysqli->begin_transaction();
            try {
                $stmt_update_hoadon = $mysqli->prepare("UPDATE hoadon SET Status = ? WHERE IdHD = ?");
                if (!$stmt_update_hoadon) {
                    throw new Exception("Lỗi chuẩn bị truy vấn cập nhật hóa đơn: " . $mysqli->error);
                }
                $stmt_update_hoadon->bind_param("ii", $newStatus, $orderId);

                if ($stmt_update_hoadon->execute()) {
                    $mainUpdateSuccess = true;

                    // Nếu trạng thái mới là "Đã hủy" (Status = 4)
                    if ($newStatus == 4) {
                        $stmt_get_items = $mysqli->prepare("SELECT IdSP, Quantity FROM chitiethoadon WHERE IdHD = ?");
                        if (!$stmt_get_items) {
                            throw new Exception("Lỗi chuẩn bị truy vấn lấy chi tiết đơn hàng: " . $mysqli->error);
                        }
                        $stmt_get_items->bind_param("i", $orderId);
                        $stmt_get_items->execute();
                        $result_items = $stmt_get_items->get_result();

                        if ($result_items->num_rows > 0) {
                            while ($item = $result_items->fetch_assoc()) {
                                $productId = intval($item['IdSP']);
                                $quantityInOrder = intval($item['Quantity']);

                                $stmt_update_stock = $mysqli->prepare("UPDATE sanpham SET Quantity = Quantity + ? WHERE IdSP = ?");
                                if (!$stmt_update_stock) {
                                    throw new Exception("Lỗi chuẩn bị truy vấn cập nhật kho: " . $mysqli->error);
                                }
                                $stmt_update_stock->bind_param("ii", $quantityInOrder, $productId);
                                if (!$stmt_update_stock->execute()) {
                                    $mainUpdateSuccess = false;
                                    $_SESSION['error_message'] = "Lỗi khi hoàn lại số lượng cho sản phẩm ID " . $productId . ". Trạng thái đơn hàng chưa được cập nhật hoàn toàn.";
                                    error_log("Stock update failed for product ID $productId, order ID $orderId: " . $stmt_update_stock->error);
                                    $stmt_update_stock->close();
                                    break; 
                                }
                                $stmt_update_stock->close();
                            }
                        }
                        if (isset($stmt_get_items)) $stmt_get_items->close();
                    }

                    if ($mainUpdateSuccess) {
                        $mysqli->commit();
                        if ($newStatus == 4) {
                            $_SESSION['success_message'] = "Đơn hàng #" . $orderId . " đã được hủy. Số lượng sản phẩm đã được hoàn lại kho.";
                        } else {
                            $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng #" . $orderId . " thành công.";
                        }
                    } else {
                        $mysqli->rollback();
                        if (empty($_SESSION['error_message'])) { 
                            $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật kho cho đơn hàng #" . $orderId . ". Thay đổi đã được hoàn tác.";
                        }
                    }
                } else { 
                    $mysqli->rollback();
                    $_SESSION['error_message'] = "Lỗi khi cập nhật trạng thái đơn hàng #" . $orderId . ": " . $stmt_update_hoadon->error;
                    error_log("Hoadon status update execute failed for order ID $orderId: " . $stmt_update_hoadon->error);
                }
                if (isset($stmt_update_hoadon)) $stmt_update_hoadon->close();
            } catch (Exception $e) {
                $mysqli->rollback();
                $_SESSION['error_message'] = "Lỗi giao dịch: " . $e->getMessage();
                error_log("Transaction error for order ID $orderId: " . $e->getMessage());
            }
        } elseif ($newStatus == $currentStatus) {
            $_SESSION['error_message'] = "Đơn hàng #" . $orderId . " đã ở trạng thái này.";
        } else { // $newStatus < $currentStatus
            $_SESSION['error_message'] = "Không thể cập nhật đơn hàng #" . $orderId . " về trạng thái trước đó.";
        }
    } else {
        $_SESSION['error_message'] = "Không tìm thấy đơn hàng #" . $orderId . ".";
    }
    if (isset($stmt_get_current)) $stmt_get_current->close();

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Lấy các biến filter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$searchTerm = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';
$locationFilter = isset($_GET['location']) ? trim($_GET['location']) : '';

$provinceFilter = isset($_GET['province']) ? $_GET['province'] : '';
$districtFilter = isset($_GET['district']) ? $_GET['district'] : '';
$wardFilter = isset($_GET['ward']) ? $_GET['ward'] : '';

// Xây dựng câu lệnh SQL
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

// Điều kiện này sẽ loại bỏ những đơn hàng có ngày đặt bằng hoặc sau ngày dự kiến giao.
// Hãy chắc chắn đây là logic bạn mong muốn.
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
            <input type="date" class="form-control" name="from_date" id="from_date" value="<?php echo htmlspecialchars($fromDate); ?>">
        </div>
        <div class="col-sm-auto">
            <label class="visually-hidden" for="to_date">Đến ngày:</label>
            <input type="date" class="form-control" name="to_date" id="to_date" value="<?php echo htmlspecialchars($toDate); ?>">
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
                                <form method="post" action="" style="margin: 0;" class="form-update-status">
                                    <input type="hidden" name="order_id" value="<?php echo $row['IdHD']; ?>">
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
