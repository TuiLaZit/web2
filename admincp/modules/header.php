<div class="header">
    <div class="header-left">
        <div class="logo">
            <a href="index.php"><img src="" alt="Logo Admin"></a>
        </div>
        <div class="admin-title">
            <h2>Trang Quản Trị</h2>
        </div>
    </div>
    <div class="header-right">
        <div class="admin-info">
            <?php
                if(isset($_SESSION['admin_name'])) {
                    echo '<span>Xin chào, '.$_SESSION['admin_name'].'</span>';
                }
            ?>
            <a href="./modules/logout.php" class="logout-btn">Đăng xuất</a>
        </div>
    </div>
</div>