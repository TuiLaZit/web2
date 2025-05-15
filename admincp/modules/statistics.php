<?php
require_once(__DIR__. '/../config/config.php');
?>
<div>
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
    <script src="js/stat.js"></script>
</div>