<?php
include (__DIR__ . '/../../config/config.php');

header('Content-Type: application/json');

$DateFrom = $_GET['datefrom'] ?? date('Y-m-d');
$DateTo = $_GET['dateto'] ?? date('Y-m-d'); // Nếu không có ngày kết thúc, mặc định lấy ngày hiện tại
$sortOrder = $_GET['sortOrder'] ?? 'DESC'; // Nếu không có giá trị, mặc định là giảm dần

if (!$DateFrom) {
    // Nếu không có DateFrom, bỏ điều kiện lọc theo ngày
    $StatQuery = "
        SELECT IDKH, Name, TongTien
        FROM (
                SELECT h.IDKH, k.Name, SUM(h.Total) AS TongTien
            FROM HoaDon h
            JOIN KhachHang k ON h.IDKH = k.IDKH
            GROUP BY h.IDKH, k.Name
            ORDER BY TongTien DESC
            LIMIT 5
        ) AS TopCustomers
        ORDER BY TongTien $sortOrder;
    ";

    $stmt = $mysqli->prepare($StatQuery);
} else {
    // Nếu có DateFrom, sử dụng điều kiện lọc theo ngày
    $StatQuery = "
        SELECT IDKH, Name, TongTien
        FROM (
            SELECT h.IDKH, k.Name, SUM(h.Total) AS TongTien
            FROM HoaDon h
            JOIN KhachHang k ON h.IDKH = k.IDKH
            WHERE h.Date BETWEEN ? AND ?
            GROUP BY h.IDKH, k.Name
            ORDER BY TongTien DESC
            LIMIT 5
        ) AS TopCustomers
        ORDER BY TongTien $sortOrder;
    ";

    $stmt = $mysqli->prepare($StatQuery);
    $stmt->bind_param("ss", $DateFrom, $DateTo);
}

$stmt->execute();
$resultStat = $stmt->get_result();

//Kiểm tra nếu không có kết quả
if ($resultStat->num_rows === 0) {
    echo json_encode(["statHTML" => "<p style='text-align: center; font-style: italic;'>Không có dữ liệu hợp lệ.</p>"]);
    exit;
}
// Tạo danh sách HTML khách hàng + hóa đơn
$statHTML = '<ul>';
while ($row = $resultStat->fetch_assoc()) {
    $customerID = $row['IDKH'];

    $statHTML .= "<li>";
    $statHTML .= "<h3 class='statIDguest'>" . htmlspecialchars($row['Name']) . " - Tổng tiền: " . number_format($row['TongTien']) . " VNĐ</h3>";
    
    // Truy vấn lấy danh sách hóa đơn của từng khách hàng theo thứ tự đã chọn
    $InvoiceQuery = "
        SELECT IDHD, Total 
        FROM HoaDon 
        WHERE IDKH = ? " . ($DateFrom ? "AND Date BETWEEN ? AND ?" : "") . "
        ORDER BY Total $sortOrder;
    ";
    
    $invoiceStmt = $mysqli->prepare($InvoiceQuery);

    if ($DateFrom) {
        $invoiceStmt->bind_param("sss", $customerID, $DateFrom, $DateTo);
    } else {
        $invoiceStmt->bind_param("s", $customerID);
    }

    $invoiceStmt->execute();
    $invoiceResult = $invoiceStmt->get_result();

    $statHTML .= "<ul>";
    while ($invoiceRow = $invoiceResult->fetch_assoc()) {
        $statHTML .= "<li>";
        $statHTML .= "Mã hóa đơn: <b>" . htmlspecialchars($invoiceRow['IDHD']) . "</b> - Giá: " . number_format($invoiceRow['Total']) . " VNĐ";
        $statHTML .= "<a href='index.php?action=orders&query=details&id=" . htmlspecialchars($invoiceRow['IDHD']) . "' class='detail-button'>Xem chi tiết</a>";
        $statHTML .= "</li>";
    }
    $statHTML .= "</ul>";

    $statHTML .= "</li>";
}
$statHTML .= '</ul>';

echo json_encode(["statHTML" => $statHTML]);
?>