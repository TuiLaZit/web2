<div class="header">
    <div class="header-left">
        <div style="display: flex; align-items: center;">
            <a href="<?php echo $baseUrl ?>/admincp/index.php"><img src="<?php echo $baseUrl ?>/img/logo.png?v=<?php echo time() ?>" alt="logo" style="height: 40px; width: auto; border-radius: 35%; margin-right: 10px"></a>
            <h2 style="margin: 0px;">Trang Quản Trị</h2>
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