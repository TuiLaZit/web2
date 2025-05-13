<?php
header('Content-Type: application/json');
include(__DIR__ . '/../../admincp/config/config.php');
include(__DIR__ . '/../../class/pagination.php');

// Nhận dữ liệu từ request
$idGroup = isset($_GET['idgrp']) ? (int)$_GET['idgrp'] : 0;
$name = isset($_GET['name']) ? trim($_GET['name']) : '';
$minPrice = isset($_GET['min']) ? (float)$_GET['min'] : 0;
$maxPrice = isset($_GET['max']) ? (float)$_GET['max'] : 100000000;
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : 'Tất cả';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10;

// Xây dựng điều kiện lọc
$whereClause = "IdGRP = '$idGroup'";
if (!empty($name)) {
    $searchEscaped = mysqli_real_escape_string($mysqli, $name);
    $whereClause .= " AND LOWER(Name) LIKE LOWER('%$searchEscaped%')";
}
if ($tag !== 'Tất cả') {
    $tagEscaped = mysqli_real_escape_string($mysqli, $tag);
    $whereClause .= " AND Type = '$tagEscaped'";
}
$whereClause .= " AND Price BETWEEN $minPrice AND $maxPrice";

// Tính tổng số sản phẩm theo bộ lọc
$queryCount = "SELECT COUNT(*) as total FROM sanpham WHERE $whereClause";
$resultCount = mysqli_query($mysqli, $queryCount);
$rowCount = mysqli_fetch_assoc($resultCount);
$totalItems = $rowCount['total'] ?? 0;

$paginator = new Pagination($totalItems, $itemsPerPage, $page);
$paginationHtml = $paginator->generatePaginationHtml();

$startIndex = ($page - 1) * $itemsPerPage;
$queryProducts = "SELECT DISTINCT IdSP, Name, IMG, Price FROM sanpham WHERE $whereClause AND Quantity > 0 ORDER BY ReleaseDate DESC LIMIT $startIndex, $itemsPerPage";
$resultProducts = mysqli_query($mysqli, $queryProducts);

$productsHtml = '';
while ($row = mysqli_fetch_assoc($resultProducts)) {
    $productsHtml .= "<a href='/pages/product-detail.php?id=" . htmlspecialchars($row['IdSP']) . "' class='product-item'>";
        $productsHtml .= "<img src='../../admincp/img/products/" . htmlspecialchars($row['IMG']) . "' alt='" . htmlspecialchars($row['Name']) . "'>";
        $productsHtml .= "<h3 class='product-name'>" . htmlspecialchars($row['Name']) . "</h3>";
        $productsHtml .= "<p class='product-price'>" . htmlspecialchars($row['Price']) . " VND</p>";
    $productsHtml .= "</a>";
}

// Trả JSON
echo json_encode([
    'productsHtml' => $productsHtml ?: "<p>Không tìm thấy sản phẩm.</p>",
    'paginationHtml' => $paginationHtml
], JSON_UNESCAPED_UNICODE);
?>
