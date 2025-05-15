<div class="header">
    <div class="left-section">
        <div class="logo">
            <a href="<?php echo $baseUrl ?>/index.php"><img src="<?php echo $baseUrl ?>/img/logo.png?v=1" alt="Logo"></a>
        </div>
    </div>
    <div class="right-tab">
        <ul class="rbuttontab">
            <li>
                <a href="<?php echo $baseUrl ?>/index.php?quanly=giohang" class="rightbutton">Giỏ Hàng</a>
            </li>
            <li>
                <a href="./index.php?quanly=thongbao" class="rightbutton">Thông Báo</a>
            </li>

            <?php if (isset($_SESSION["user"])): ?>
                <li>
                    <a href="<?php echo $baseUrl?>/./controller/login-customer.php?logout" class="rightbutton">Đăng Xuất</a>
                </li>
            <?php else : ?>
                <li>
                    <a href="./login.php" class="rightbutton">Đăng Nhập</a>
                </li>
            <?php endif  ?>



        </ul>
    </div>
</div>
