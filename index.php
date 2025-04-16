<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <title>NJZ gShop</title>
</head>

<body>
    <div class="wrapper">
        <?php
            include("./admincp/config/config.php");

            session_start();
            if (isset($_SESSION["user"])) {
                $user = $_SESSION["user"];
            }

            include("pages/header.php");
            include("pages/main.php");
            include("pages/footer.php");
        ?>
    </div>
</body>

</html>