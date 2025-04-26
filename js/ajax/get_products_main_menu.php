<?php
include(__DIR__ . '/../../admincp/config/config.php'); 
include(__DIR__ . '/../../class/pagination.php'); 

$idGroup = isset($_GET['idgrp']) ? (int)$_GET['idgrp'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 4;

$resultCount = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM sanpham WHERE IdGRP = '$idGroup'");
$rowCount = mysqli_fetch_assoc($resultCount);
$totalItems = $rowCount['total'] ?? 0;

$pagination = new Pagination($totalItems, $itemsPerPage, $page);
$paginationHtml = $pagination->generatePaginationHtml();

// Tính vị trí bắt đầu
$startIndex = ($page - 1) * $itemsPerPage;
$queryProducts = "SELECT IdSP, Img, Name, Price FROM sanpham WHERE IdGRP = '$idGroup' ORDER BY ReleaseDate DESC LIMIT $startIndex, $itemsPerPage";
$resultProducts = mysqli_query($mysqli, $queryProducts);

$productsHtml = '';
while ($row = mysqli_fetch_assoc($resultProducts)) {
    $productsHtml .= "<a href='/pages/product-detail.php?id=" . htmlspecialchars($row['IdSP']) . "' class='product-item'>";
    $productsHtml .= "<img src='../../admincp/img/products/" . htmlspecialchars($row['Img']) . "' alt='" . htmlspecialchars($row['Name']) . "'>";
    $productsHtml .= "<div class='product-name'>" . htmlspecialchars($row['Name']) . "</div>";
    $productsHtml .= "<div class='product-price'>" . htmlspecialchars($row['Price']) . " VND</div>";
    $productsHtml .= "</a>";
}

echo json_encode([
    'productsHtml' => $productsHtml ?: "<p>Không có sản phẩm nào.</p>",
    'paginationHtml' => $paginationHtml
]);
?>