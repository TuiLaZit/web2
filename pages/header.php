<div class="header">
    <div class="left-section">
        <div class="logo">
            <a href="index.php"><img src="img/logo.png" alt="Logo"></a>
        </div>
        <div class="dropdown_menu">
            <button class="dropdown_button">Nhóm nhạc</button>
            <div class="dropdown_content">
                <a href="#">NJZ</a>
                <a href="#">BIG BANG</a>
                <a href="#">IVE</a>
            </div>
        </div>
    </div>
    <div class="right-tab">
        <ul class="rbuttontab">
            <li>
                <a href="index.php?quanly=giohang" class="rightbutton">Giỏ Hàng</a>
            </li>
            <li>
                <a href="index.php?quanly=thongbao" class="rightbutton">Thông Báo</a>
            </li>

            <?php if (isset($user)): ?>
                <li>
                    <div>Xin chào <?php echo $user['Name'] ?></div>
                </li>
                <li>
                    <a href="./controller/login-customer.php?logout" class="rightbutton">Đăng xuất</a>
                </li>
            <?php else: ?>
                <li>
                    <a href="login.php" class="rightbutton">Đăng Nhập</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>