<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>KCorner</title>
</head>

<body>
    <div class="wrapper">
        <?php
            include("./admincp/config/config.php");
            include("./utils.php");

            session_start();
            if (isset($_SESSION["user"])) {
                $user = $_SESSION["user"];
                if (isset($user['isAdmin']) && $user['isAdmin']) {
                    redirect("./admincp");
                }
            }

            include("pages/header.php");
            include("pages/main/main.php");
            include("pages/footer.php");
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
