<?php
    $sql_sua_nhanvien = "SELECT * FROM nhanvien WHERE IdNV='".mysqli_real_escape_string($mysqli, $_GET['idstaff'])."' LIMIT 1";
    $query_sua_nhanvien = mysqli_query($mysqli,$sql_sua_nhanvien);
?>
<link rel="stylesheet" type="text/css" href="./css/quan-ly-nhan-vien.css?v=<?php echo time(); ?>">
<a href="javascript:history.back()" class=" btn btn-secondary">Quay lại</a>
<p>Sửa Nhân Viên</p>
<table class="tableadmin" border="1" style="width:50%; border-collapse: collapse;">
    <form method="POST" action="modules/nhanvien/xuly.php?IdNV=<?php echo htmlspecialchars($_GET['idstaff']); ?>">
        <?php
            if ($query_sua_nhanvien && mysqli_num_rows($query_sua_nhanvien) > 0) {
                while($dong = mysqli_fetch_array($query_sua_nhanvien)){
                    $decryptedPass = '';
                    if (isset($key) && !empty($dong['Password'])) {
                        $decryptedPass = openssl_decrypt($dong['Password'],'AES-128-ECB',$key);
                        if ($decryptedPass === false) {
                            $decryptedPass = "Lỗi giải mã mật khẩu";
                        }
                    } else if (empty($dong['Password'])) {
                        $decryptedPass = "";
                    } else {
                        $decryptedPass = "Không thể hiển thị mật khẩu (lỗi cấu hình)";
                    }
        ?>
        <tr>
            <td>ID</td>
            <td><input type="text" name="ID" value="<?php echo htmlspecialchars($dong['IdNV']); ?>" readonly></td>
        </tr>
        <tr>
            <td>Tài Khoản</td>
            <td><input type="text" name="Account" value="<?php echo htmlspecialchars($dong['Account']); ?>"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" name="Password" value="<?php echo htmlspecialchars($decryptedPass); ?>"></td>
        </tr>
        <tr>
            <td>Tên</td>
            <td><input type="text" name="Name" value="<?php echo htmlspecialchars($dong['Name']); ?>"></td>
        </tr>
        <tr>
            <td>Số điện thoại</td>
            <td><input type="text" name="Phone" value="<?php echo htmlspecialchars($dong['PNumber']); ?>"></td>
        </tr>
        <tr>
            <td>Địa chỉ</td>
            <td><input type="text" name="Address" value="<?php echo htmlspecialchars($dong['Address']); ?>"></td>
        </tr>
        <tr>
            <td>Vị trí</td>
            <td><input type="text" name="Position" value="<?php echo htmlspecialchars($dong['IdPos']); ?>"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="FixNV" value="Sửa nhân viên">
            </td>
        </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='2'>Không tìm thấy thông tin nhân viên.</td></tr>";
                if (!$query_sua_nhanvien) {
                    echo "<tr><td colspan='2'>Lỗi truy vấn: " . mysqli_error($mysqli) . "</td></tr>";
                }
            }
        ?>
    </form>
</table>