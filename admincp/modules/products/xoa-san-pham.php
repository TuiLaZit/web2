<?php
require_once('../../config/config.php');
require_once("../../../utils.php");

if (isset($_GET['IdSP'])) {
    $IdSP = $_GET['IdSP'];

    // Kiểm tra xem sản phẩm đã được bán ra chưa
    $check_query = "SELECT COUNT(*) as count FROM chitiethoadon WHERE IdSP = ?";

    $check_stmt = mysqli_prepare($mysqli, $check_query);
    if (!$check_stmt) {
        die('SQL prepare error: ' . mysqli_error($mysqli));
    }

    mysqli_stmt_bind_param($check_stmt, 'i', $IdSP);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check_stmt);

    if ($row['count'] > 0) {
        // Sản phẩm đã được bán ra
        $update_query = "UPDATE sanpham SET Status = 2 WHERE IdSP = ?";

        $update_stmt = mysqli_prepare($mysqli, $update_query);
        if (!$update_stmt) {
            responseJson([
                'success' => false,
                'messages' => 'SQL prepare error: ' . mysqli_error($mysqli)
            ]);
        }

        mysqli_stmt_bind_param($update_stmt, 'i', $IdSP);

        if (mysqli_stmt_execute($update_stmt)) {
            mysqli_stmt_close($update_stmt);
            mysqli_close($mysqli);
            responseJson([
                'success' => true,
                'messages' => 'Sản phẩm đã được bán ra, không thể xóa. Đã ẩn sản phẩm!'
            ]);
        } else {
            mysqli_close($mysqli);
            responseJson([
                'success' => false,
                'messages' => 'Database error: ' . mysqli_stmt_error($update_stmt)
            ]);
        }
    } else {
        // Sản phẩm chưa được bán ra, có thể xóa
        // Kiểm tra xem đã xác nhận xóa chưa
        if (isset($_GET['confirm']) && $_GET['confirm'] == 1) {
            // Đã xác nhận, tiến hành xóa
            $delete_query = "DELETE FROM sanpham WHERE IdSP = ?";

            $delete_stmt = mysqli_prepare($mysqli, $delete_query);
            if (!$delete_stmt) {
                mysqli_close($mysqli);
                responseJson([
                    'success' => false,
                    'messages' => 'SQL prepare error: ' . mysqli_error($mysqli)
                ]);
            }

            mysqli_stmt_bind_param($delete_stmt, 'i', $IdSP);

            if (mysqli_stmt_execute($delete_stmt)) {
                mysqli_close($mysqli);
                responseJson([
                    'success' => true,
                    'messages' => 'Xóa sản phẩm thành công!'
                ]);
            } else {
                mysqli_close($mysqli);
                responseJson([
                    'success' => false,
                    'messages' => 'Database error: ' . mysqli_stmt_error($delete_stmt)
                ]);
            }
        } else {
            // Chưa xác nhận, hiển thị thông báo xác nhận
            mysqli_close($mysqli);
            // Chuyển hướng đến trang xác nhận
            responseJson([
                'isOpenDialog' => true,
            ]);
        }
    }
} else {
    responseJson([
        'success' => false,
        'messages' => 'Invalid request id.'
    ]);
}
