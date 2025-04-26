document.addEventListener("DOMContentLoaded", function () {
    function loadProducts(groupId, page = 1) {
        const productGrid = document.getElementById(`product-grid-${groupId}`);
        const paginationContainer = document.getElementById(`pagination-container-${groupId}`);
        
        // Gửi yêu cầu AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `js/ajax/get_products_main_menu.php?idgrp=${groupId}&page=${page}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                productGrid.innerHTML = response.productsHtml; // Cập nhật sản phẩm
                paginationContainer.innerHTML = response.paginationHtml; // Cập nhật nút phân trang
                attachPaginationEvents(groupId); // Gắn sự kiện mới cho các nút phân trang
            }
        };
        xhr.send();
    }

    function attachPaginationEvents(groupId) {
        const paginationLinks = document.querySelectorAll(`#pagination-container-${groupId} .pagination-link`);
        paginationLinks.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const page = this.getAttribute("data-page");
                loadProducts(groupId, page); // Tải sản phẩm cho trang được chọn
            });
        });
    }

    // Khởi động AJAX cho từng nhóm
    const groupContainers = document.querySelectorAll(".group-container");
    groupContainers.forEach(container => {
        const groupId = container.id.split("-")[2]; // Lấy ID nhóm
        loadProducts(groupId); // Tải dữ liệu ban đầu cho nhóm
    });
});
