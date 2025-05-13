<div id="main" class="main">
    <?php
    if (isset($_GET['quanly'])) {
        switch ($_GET['quanly']) {
            case 'giohang':
                include __DIR__ . './../cart.php';
                break;
            default:
                include("banner.php");
                echo '<div id="main-page">';
                include("searchbar.php");
                echo '<div id="main-container">';
                include("main_page_container.php");
                echo '</div></div>';
                break;
        }
    } else {
        include("banner.php");
        echo '<div id="main-page">';
        include("searchbar.php");
        echo '<div id="main-container">';
        include("main_page_container.php");
        echo '</div></div>';
    }
    ?>
</div>
