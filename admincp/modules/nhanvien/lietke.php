<?php
    $sql_lietke_nhanvien = "SELECT `IdNV`, `Account`, `Name`, `PNumber`, `Address`, `Status`, `IdPos` FROM `nhanvien` WHERE Status=1";
    $query_lietke_nhanvien =mysqli_query($mysqli,$sql_lietke_nhanvien);
?>
<div class="staff-list">
    <?php
    if(isset($_SESSION['message'])) {
        $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
    ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $_SESSION['message']; ?>
            <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
        </div>
    <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <h2>Quản lý nhân viên</h2>
    <a href="index.php?action=staff&query=them" class="btn-add">Thêm nhân viên mới</a>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Tên</th>
                <th>Số Điện Thoại</th>
                <th>Địa chỉ</th>
                <th>Tình Trạng</th>
                <th>Chức Vụ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Nếu có dữ liệu
            if($query_lietke_nhanvien && mysqli_num_rows($query_lietke_nhanvien) > 0) {
                while($row = mysqli_fetch_array($query_lietke_nhanvien)) {
            ?>
                <tr>
                    <td><?php echo $row['IdNV']?></td>
                    <td><?php echo $row['Account']?></td>
                    <td><?php echo $row['Name']?></td>
                    <td><?php echo $row['PNumber']?></td>
                    <td><?php echo $row['Address']?></td>
                    <td style="width:10px"><?php echo $row['Status']?></td>
                    <td><?php echo $row['IdPos']?></td>
                    <td class="action-buttons">
                        <a href="?action=staff&query=sua&idstaff=<?php echo $row['IdNV']?>" class="btn-edit">Sửa</a>
                        <a href="?action=staff&query=xoa&idstaff=<?php echo $row['IdNV']?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?');">Xóa</a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="8" class="no-data">Không có dữ liệu nhân viên</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    
    <!-- Phân trang nếu có -->
    <div class="pagination">
        <!-- Code phân trang ở đây -->
    </div>
</div>