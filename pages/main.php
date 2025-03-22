<div class="main">
        <?php
        if(isset($_GET['quanly'])){
            $tam = $_GET['quanly'];
        }else{
            $tam = '';
        }
        if($tam==''){
            include("main/main_menu.php");
        };
        ?>
</div>