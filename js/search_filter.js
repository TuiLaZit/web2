document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.getElementById("search-button");

    searchButton.addEventListener("click", function () {
        const searchName = document.getElementById("search-box").value.trim();
        const minPrice = document.getElementById("min-price").value || 0;
        const maxPrice = document.getElementById("max-price").value || 100000000;
        const groupId = new URLSearchParams(window.location.search).get("idgrp");

        if (!groupId) {
            console.error("Không tìm thấy ID nhóm.");
            return;
        }

        // Kiểm tra tag nào đang active
        const activeTag = document.querySelector(".tag-item.active");
        const selectedTag = activeTag ? activeTag.getAttribute("data-tag") : "Tất cả";

        loadFilteredProducts(groupId, searchName, minPrice, maxPrice, selectedTag, 1); // Gửi tag active vào
    });

    function loadFilteredProducts(groupId, name, minPrice, maxPrice, tag, page) {
        const productList = document.getElementById("products-list");
        const paginationContainer = document.getElementById("pagination_switch");

        if (!productList || !paginationContainer) {
            console.error("Không tìm thấy phần tử danh sách sản phẩm hoặc phân trang.");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../js/ajax/filter_products.php?idgrp=${groupId}&name=${encodeURIComponent(name)}&min=${minPrice}&max=${maxPrice}&tag=${encodeURIComponent(tag)}&page=${page}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    productList.innerHTML = response.productsHtml;
                    paginationContainer.innerHTML = response.paginationHtml;

                    attachPaginationEvents(groupId, name, minPrice, maxPrice, tag); // Cập nhật sự kiện phân trang
                } catch (error) {
                    console.error("Lỗi xử lý phản hồi JSON:", error);
                }
            }
        };
        xhr.send();
    }

    function attachPaginationEvents(groupId, name, minPrice, maxPrice, tag) {
        document.querySelectorAll(".pagination-link").forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const page = this.getAttribute("data-page");
                loadFilteredProducts(groupId, name, minPrice, maxPrice, tag, page); // Giữ tag đang chọn khi phân trang
            });
        });
    }

    // Tải dữ liệu ban đầu với toàn bộ sản phẩm và tag mặc định
    const groupId = new URLSearchParams(window.location.search).get("idgrp");
    if (groupId) {
        const defaultTag = document.querySelector(".tag-item.active") ? document.querySelector(".tag-item.active").getAttribute("data-tag") : "Tất cả";
        loadFilteredProducts(groupId, "", 0, 100000000, defaultTag, 1);
    } else {
        console.error("Không tìm thấy ID nhóm từ URL.");
    }
});
