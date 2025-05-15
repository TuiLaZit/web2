<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/styleadmincp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/login-admin.css?v=<?php echo time(); ?>" />
    <title>Trang Quản Trị</title>
</head>

<body>
    <div class="wrapper">
        <?php
        include("config/config.php");
        include("../utils.php");

        // Start the session to access error messages
        session_start();

        if (isset($_SESSION['user'])) {
            redirect("./index.php");
        }

        $loginErrors = [];
        if (isset($_SESSION['login_errors'])) {
            $loginErrors = $_SESSION['login_errors'];
            unset($_SESSION['login_errors']);
        }
        ?>

        <div class="wrapper login">
            <div class="container">
                <!-- Left Column -->
                <div class="col-left">
                    <div class="login-text">
                        <h2>Chào mừng trở lại</h2>
                        <p>Nếu bạn là khách hàng lạc lối.<br>Vào trang khách hàng.</p>
                        <a href="../login.php" class="btn">Khách hàng</a>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-right">
                    <div class="login-form">
                        <!-- Logo -->
                        <img src="<?php echo $baseUrl?>/img/logo.png?v=<?php echo time()?>" alt="Logo" class="logo" style="border-radius: 35%;">
                        <h2>Trang quản trị</h2>
                        <p class="slogan">Kiểm soát mọi vấn đề của bạn!</p>
                        <form id="formLogin" action="./modules/login-handler.php" method="post">
                            <input name="login" type="text" hidden style="display: none;" />
                            <p>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    placeholder="Tên đăng nhập"
                                    required />
                            </p>
                            <p>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Mật khẩu"
                                    required />
                            </p>
                            <p>
                                <?php
                                foreach ($loginErrors as $error) {
                                    echo $error;
                                }
                                ?>
                            </p>
                            <p>
                                <input type="submit" value="Đăng nhập" />
                            </p>
                            <!-- <p>
                            <a href="#" onclick="forgotPassword()">Quên mật khẩu?</a>
                        </p> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/login-admin.js?v=<?php echo time(); ?>"></script>
</body>

</html>