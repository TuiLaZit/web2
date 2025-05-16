<?php
include("../admincp/config/config.php");
include("../class/group.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];

if (isset($_GET['idgrp'])) {
    $id = $_GET['idgrp'];
    $group = group::getGroup($id);

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="../css/group.css">
        <title><?php echo isset($group) ? htmlspecialchars($group->name) .  "- KCorner" : "KCorner"; ?></title>
    </head>

    <body>
        <div class="wrapper">
            <?php include("header.php"); ?>
            <?php
            if ($group) {
            ?>
                <div id="main-page">
                    <div id="group">
                        <img src="<?php echo $baseUrl ?>/admincp/img/groups/<?php echo htmlspecialchars($group->img); ?>" alt="<?php echo htmlspecialchars($group->name); ?>">
                        <div id="group-info">
                            <h1><?php echo htmlspecialchars($group->name); ?></h1>
                            <p>Công Ty: <?php echo htmlspecialchars($group->company); ?></p>
                            <p><?php echo htmlspecialchars($group->info); ?></p>
                        </div>
                    </div>
                    <div id="shop">
                        <div id="tags">
                            <button class="tag-item" data-tag="Tất cả">Tất cả</button>
                            <?php
                            $sql_get_type = "SELECT Type, COUNT(*) as CType FROM sanpham WHERE Status=1 AND IdGRP='$id' GROUP BY Type;";
                            $query_type = mysqli_query($mysqli, $sql_get_type);
                            if ($query_type && mysqli_num_rows($query_type) > 0) {
                                while ($row = mysqli_fetch_array($query_type)) {
                                    echo "<button class='tag-item' data-tag='" . $row['Type'] . "'>" . $row['Type'] . "</button>";
                                }
                            }
                            ?>
                        </div>

                        <div id="search">
                            <input type="text" id="search-box" placeholder="Tìm kiếm tên sản phẩm...">
                            <input type="number" id="min-price" placeholder="Giá tối thiểu">
                            <input type="number" id="max-price" placeholder="Giá tối đa">
                            <button id="search-button">Tìm kiếm</button>
                        </div>
                        <div id="products">
                            <div id="products-list"></div>
                            <div id="pagination_switch"></div>
                        </div>
                <?php
            } else {
                echo "<p>nhóm không tồn tại!</p>";
            }
        } else {
            echo "<p>Không tìm thấy ID nhóm!</p>";
        }
                ?>
                    </div>
                </div>
                <?php
                include("footer.php");
                ?>
        </div>
        <script src="../js/tags_filter.js?v=<?php echo time() ?>"></script>
        <script src="../js/search_filter.js?v=<?php echo time() ?>"></script>
        </div>
    </body>

    </html>
