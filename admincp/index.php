<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/styleadmincp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="./css/quan-ly-san-pham.css?v=<?php echo time(); ?>">
    <title>KCorner</title>
    <link rel="icon" href="./img/logo.png?v=<?php echo time() ?>" type="image/png">
</head>

<body>
    <div class="wrapper">
        <?php
        include("config/config.php");
        include("../utils.php");

        session_start();


        if (!isset($_SESSION['user'])) {
            redirect('./login-admin.php');
        }

        $user = $_SESSION['user'];

        if (!$user['isAdmin']) {
            redirect('../index.php');
        }

        include("modules/header.php");
        include("modules/menu.php");
        include("modules/main.php");
        ?>
    </div>
</body>

</html>