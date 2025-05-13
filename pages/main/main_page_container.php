<?php
    include('./class/product_list.php');
    include('./admincp/config/config.php');
    $menugrpsql = "SELECT IdGRP From nhom ORDER BY IdGRP ASC LIMIT 3;";
    $query_menu_grp = mysqli_query($mysqli,$menugrpsql);

    $IDGroups=[];
    while($row = mysqli_fetch_array($query_menu_grp)){
        $IDGroups[]=$row['IdGRP'];
    }
    foreach($IDGroups as $IDgroup){
        $group = group::getGroup($IDgroup);
        echo "<div id='group-container-" . htmlspecialchars($group->id) . "' class='group-container'>";
            
            // Tên nhóm
            echo "<a href='./../pages/group.php?idgrp=" . htmlspecialchars($group->id) . "' class='group-name'>";
                echo "<p>" . htmlspecialchars($group->name) . "</p>";
            echo "</a>";
        
            // Vùng sản phẩm (dữ liệu sẽ được tải qua AJAX)
            echo "<div id='product-grid-" . htmlspecialchars($group->id) . "' class='product-grid'>";
                echo "<p>Loading products...</p>";
            echo "</div>";
        
            // Vùng phân trang (dữ liệu sẽ được tải qua AJAX)
            echo "<div id='pagination-container-" . htmlspecialchars($group->id) . "' class='pagination-container'>";
            echo "</div>";
        
        echo "</div>"; // Kết thúc container
    }
    echo "<script src='./js/mainmenu.js'></script>";
?>