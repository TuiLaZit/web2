<?php
    $sql_sua_nhanvien = "SELECT * FROM nhanvien WHERE IdNV='$_GET[idstaff]' LIMIT 1";
    $query_sua_nhanvien = mysqli_query($mysqli,$sql_sua_nhanvien);
?>
<link rel="stylesheet" type="text/css" href="./css/quan-ly-nhan-vien.css?v=<?php echo time(); ?>">
<a href="javascript:history.back()" class=" btn btn-secondary">Quay lại</a>
<p>Sửa Nhân Viên</p>
<table class="tableadmin" border="1" style="width:50%" style="border-collapse: collapse;">
    <form method="POST" action="modules/nhanvien/nhanvien/xuly.php?IdNV=<?php echo $_GET['idstaff']?>">
        <?php
            while($dong = mysqli_fetch_array($query_sua_nhanvien)){
                $decryptedPass = openssl_decrypt($dong['Password'],'AES-128-ECB',$key);
        ?>
        <tr>
            <td>ID</td>
            <td><input type="text" name="ID" value="<?php echo $dong['IdNV']?>"></td>
        </tr>
        <tr>
            <td>Tài Khoản</td>
            <td><input type="text" name="Account" value="<?php echo $dong['Account']?>"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" name="Password" value="<?php echo $decryptedPass ?>"></td>
        </tr>
        <tr>
            <td>Tên</td>
            <td><input type="text" name="Name" value="<?php echo $dong['Name']?>"></td>
        </tr>
        <tr>
            <td>Số điện thoại</td>
            <td><input type="text" name="Phone" value="<?php echo $dong['PNumber']?>"></td>
        </tr>
        <tr>
            <td>Địa chỉ</td>
            <td><input type="text" name="Address" value="<?php echo $dong['Address']?>"></td>
        </tr>
        <tr>
            <td>Vị trí</td>
            <td><input type="text" name="Position" value="<?php echo $dong['IdPos']?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="FixNV" value="Sửa nhân viên">
            </td>
        </tr>
        <?php
            }
        ?>
    </form>
</table>
