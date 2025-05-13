<?php
require_once('../../config/config.php');
require_once('../../../utils.php');

if (isset($_GET['IdGRP'])) {
    $IdGRP = $_GET['IdGRP'];
    
    // First check if there are any products in this group
    $check_query = "SELECT COUNT(*) as count FROM sanpham WHERE IdGRP = ?";
    
    $check_stmt = mysqli_prepare($mysqli, $check_query);
    if (!$check_stmt) {
        responseJson([
            'success' => false,
            'message' => 'SQL prepare error: ' . mysqli_error($mysqli)
        ]);
    }
    
    mysqli_stmt_bind_param($check_stmt, 'i', $IdGRP);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check_stmt);
    
    // If products exist in this group, don't delete and return message
    if ($row['count'] > 0) {
        responseJson([
            'success' => false,
            'message' => 'Có sản phẩm thuộc nhóm này trong hệ thống. Không thể xóa nhóm này!'
        ]);
    }
    
    // No products in this group, proceed with deletion
    $delete_query = "DELETE FROM nhom WHERE IdGRP = ?";
    
    $delete_stmt = mysqli_prepare($mysqli, $delete_query);
    if (!$delete_stmt) {
        responseJson([
            'success' => false,
            'message' => 'SQL prepare error: ' . mysqli_error($mysqli)
        ]);
    }
    
    mysqli_stmt_bind_param($delete_stmt, 'i', $IdGRP);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        responseJson([
            'success' => true,
            'message' => 'Xóa nhóm thành công!'
        ]);
    } else {
        responseJson([
            'success' => false,
            'message' => 'Database error: ' . mysqli_stmt_error($delete_stmt)
        ]);
    }
    
    mysqli_stmt_close($delete_stmt);
    mysqli_close($mysqli);
} else {
    responseJson([
        'success' => false,
        'message' => 'Invalid request id.'
    ]);
}