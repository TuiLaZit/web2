<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json"); // Đảm bảo phản hồi JSON

include(__DIR__."/../../admincp/config/config.php"); // Đảm bảo kết nối DB đúng

if (!$mysqli) {
    echo json_encode(["error" => "Lỗi kết nối Database"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['query'])) {
    $searchname = mysqli_real_escape_string($mysqli, $_POST['query']);
    $querysearch = "SELECT IdGRP, IMG, Name FROM nhom WHERE Status = 1 ORDER BY Name ASC;";

    if (!empty($searchname)) {
        $querysearch .= " AND Name LIKE '%$searchname%'";
    }

    $result = mysqli_query($mysqli, $querysearch);
    if (!$result) {
        echo json_encode(["productsHtml" => "<div class='no-results'>Lỗi truy vấn.</div>"]);
        exit;
    }

    $groupsHtml = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $groupsHtml .= "<a class='group-item' href='./pages/group.php?idgrp=" . htmlspecialchars($row['IdGRP']) . "'>";
        $groupsHtml .= "<img src='./admincp/img/groups/" . htmlspecialchars($row['IMG']) . "' alt='" . htmlspecialchars($row['Name']) . "'>";
        $groupsHtml .= "<h3>" . htmlspecialchars($row['Name']) . "</h3>";
        $groupsHtml .= "</a>";
    }

    if (mysqli_num_rows($result) == 0) {
        $groupsHtml .= "<div class='no-results'>Không tìm thấy nhóm nào.</div>";
    }

    echo json_encode(["productsHtml" => $groupsHtml], JSON_UNESCAPED_UNICODE);
    exit;
}
?>