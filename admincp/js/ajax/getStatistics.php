<?php
include (__DIR__ . '/../../config/config.php');

header('Content-Type: application/json');

$DateFrom = $_GET['datefrom'] ?? null;
$DateTo = $_GET['dateto'] ?? null;

if (!$DateFrom || !$DateTo) {
    echo json_encode(["statHTML" => "<p>Vui lòng nhập ngày hợp lệ.</p>"]);
    exit;
}

// Truy vấn để lấy 5 khách hàng có tổng tiền mua nhiều nhất
$StatQuery = "
    SELECT h.IDKH, k.Name, SUM(h.Total) AS TongTien
    FROM HoaDon h
    JOIN KhachHang k ON h.IDKH = k.IDKH
    WHERE h.Date BETWEEN ? AND ?
    GROUP BY h.IDKH, k.Name
    ORDER BY TongTien DESC
    LIMIT 5;
";

$stmt = $mysqli->prepare($StatQuery);
$stmt->bind_param("ss", $DateFrom, $DateTo);
$stmt->execute();
$resultStat = $stmt->get_result();

// Tạo danh sách HTML khách hàng + hóa đơn
$statHTML = '<ul>';
while ($row = $resultStat->fetch_assoc()) {
    $customerID = $row['IDKH'];

    $statHTML .= "<li>";
    $statHTML .= "<h3 class='statIDguest'>" . htmlspecialchars($row['Name']) . " - Tổng tiền: " . number_format($row['TongTien']) . " VNĐ</h3>";
    
    // Truy vấn lấy danh sách hóa đơn của từng khách hàng
    $InvoiceQuery = "
        SELECT IDHD, Total 
        FROM HoaDon 
        WHERE IDKH = ? AND Date BETWEEN ? AND ?
        ORDER BY Date DESC;
    ";
    
    $invoiceStmt = $mysqli->prepare($InvoiceQuery);
    $invoiceStmt->bind_param("sss", $customerID, $DateFrom, $DateTo);
    $invoiceStmt->execute();
    $invoiceResult = $invoiceStmt->get_result();

    $statHTML .= "<ul>";
    while ($invoiceRow = $invoiceResult->fetch_assoc()) {
        $statHTML .= "<li>";
        $statHTML .= "Mã hóa đơn: <b>" . htmlspecialchars($invoiceRow['IDHD']) . "</b> - Giá: " . number_format($invoiceRow['Total']) . " VNĐ";
        $statHTML .= " <button onclick='viewOrderDetails(" . $invoiceRow['IDHD'] . ")'>Xem chi tiết</button>";
        $statHTML .= "</li>";
    }
    $statHTML .= "</ul>";

    $statHTML .= "</li>";
}
$statHTML .= '</ul>';

echo json_encode(["statHTML" => $statHTML]);
?>