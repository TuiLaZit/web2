<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/styleadmincp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="./css/quan-ly-san-pham.css?v=<?php echo time(); ?>">
    <title>Trang Quản Trị</title>
</head>

<body>
    <div class="wrapper">
        <?php
            include("config/config.php");
            include("modules/header.php");
            include("modules/menu.php");
            include("modules/main.php");
            include("modules/footer.php");
        ?>
    </div>
</body>

</html>