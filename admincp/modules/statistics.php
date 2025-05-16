<?php
require_once(__DIR__. '/../config/config.php');
?>
<link rel="stylesheet" type="text/css" href="./css/stat.css?v=<?php echo time(); ?>">
<div style="position: relative; width: 100%; height: calc(100vh - 54px);">
  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
    <div
            class="bg-color"
            style="height: 100vh; width: 100%; overflow-y: auto; position: relative;">
            <h1>Thống kê hóa đơn</h1>
            <div id="search-stat">
                <input type="date" id="dateFrom">
                <input type="date" id="dateTo">

                <select id="sortOrder">
                    <option value="ASC">Tăng dần</option>
                    <option value="DESC">Giảm dần</option>
                </select>


                <button id="searchStat">Tìm Kiếm</button>
            </div>
            <div id="stat">
                <div id="liststat"></div>
            </div>
    </div>
    </div>
</div>
<script src="js/stat.js"></script>
