<link rel="stylesheet" type="text/css" href="./css/quan-ly-nhan-vien.css?v=<?php echo time(); ?>">
<a href="javascript:history.back()" class=" btn btn-secondary">Quay lại</a>
<p>Thêm Nhân Viên</p>
<table class="tableadmin" border="1" style="width:50%; border-collapse: collapse;"> <form method="POST" action="modules/nhanvien/xuly.php">
        <tr>
            <td>Tài Khoản</td>
            <td><input type="text" name="Account" required></td> </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" name="Password" required></td> </tr>
        <tr>
            <td>Tên</td>
            <td><input type="text" name="Name" required></td> </tr>
        <tr>
            <td>Số điện thoại</td>
            <td><input type="text" name="Phone"></td>
        </tr>
        <tr>
            <td>Địa chỉ</td>
            <td><input type="text" name="Address"></td>
        </tr>
        <tr>
            <td>Vị trí</td>
            <td><input type="text" name="Position"></td> </tr>
        <tr>
            <td colspan="2" style="text-align: center;"> <input type="submit" name="AddNV" value="Thêm nhân viên">
            </td>
        </tr>
    </form>
</table>