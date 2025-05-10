<?php
include('../../config/config.php');

if(isset($_POST['IdGRP'])) {
    // Lấy thông tin từ form
    $idGRP = $_POST['IdGRP'];
    $name = $_POST['Name'];
    $company = $_POST['Company'];
    $info = $_POST['Info'];
    
    // Xử lý upload ảnh nếu có
    if(isset($_FILES['IMG']) && $_FILES['IMG']['error'] === 0) {
        $img = $_FILES['IMG']['name'];
        $img_tmp = $_FILES['IMG']['tmp_name'];
        
        // Di chuyển file ảnh vào thư mục
        move_uploaded_file($img_tmp, '../../img/groups/'.$img);
        
        // Cập nhật thông tin nhóm có ảnh mới
        $sql_update = "UPDATE `nhom` SET 
            `Name`='$name',
            `Company`='$company',
            `Info`='$info',
            `IMG`='$img'
            WHERE `IdGRP`='$idGRP'";
    } else {
        // Cập nhật thông tin nhóm không có ảnh mới
        $sql_update = "UPDATE `nhom` SET 
            `Name`='$name',
            `Company`='$company',
            `Info`='$info'
            WHERE `IdGRP`='$idGRP'";
    }
    
    // Thực hiện query
    $query_update = mysqli_query($mysqli, $sql_update);
    
    if($query_update) {
        // Nếu cập nhật thành công
        header('Location: ../../index.php?action=groups&message=success');
    } else {
        // Nếu có lỗi
        echo "Lỗi: " . mysqli_error($mysqli);
    }
} else {
    // Nếu không có ID nhóm
    header('Location: ../../index.php?action=groups&message=error');
}
?> 