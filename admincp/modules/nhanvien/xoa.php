<?php
if(isset($_GET['idstaff'])) {
    $id = $_GET['idstaff'];
    
    // Thay vì xóa hoàn toàn, chúng ta sẽ cập nhật trạng thái Status thành 0
    $sql_update = "UPDATE nhanvien SET Status = 0 WHERE IdNV = '$id'";
    $query = mysqli_query($mysqli, $sql_update);
    
    if($query) {
        $_SESSION['message'] = "Đã xóa nhân viên thành công!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Có lỗi xảy ra khi xóa nhân viên!";
        $_SESSION['message_type'] = "error";
    }
    
    // Chuyển hướng về trang danh sách nhân viên
    header('Location: index.php?action=staff');
}
?> 