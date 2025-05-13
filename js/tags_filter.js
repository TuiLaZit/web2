document.addEventListener("DOMContentLoaded", function () {
    const tagButtons = document.querySelectorAll(".tag-item");
    let activeTagButton = document.querySelector(".tag-item[data-tag='Tất cả']");

    if (activeTagButton) {
        activeTagButton.classList.add("active");
        activeTagButton.disabled = true;
    }

    const groupId = new URLSearchParams(window.location.search).get("idgrp");
    loadFilteredProducts(groupId, "", 0, 100000000, "Tất cả", 1); // Bắt đầu từ trang 1

    tagButtons.forEach(button => {
        button.addEventListener("click", function () {
            if (!this.classList.contains("active")) {
                if (activeTagButton) {
                    activeTagButton.classList.remove("active");
                    activeTagButton.disabled = false;
                }

                this.classList.add("active");
                this.disabled = true;
                activeTagButton = this;

                loadFilteredProducts(groupId, "", 0, 100000000, this.getAttribute("data-tag"), 1); // Reset về trang 1
            }
        });
    });

    function loadFilteredProducts(groupId, name, minPrice, maxPrice, tag, page) {
        const productList = document.getElementById("products-list");
        const paginationContainer = document.getElementById("pagination_switch");

        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../js/ajax/filter_products.php?idgrp=${groupId}&name=${encodeURIComponent(name)}&min=${minPrice}&max=${maxPrice}&tag=${encodeURIComponent(tag)}&page=${page}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                productList.innerHTML = response.productsHtml;
                paginationContainer.innerHTML = response.paginationHtml;
                attachPaginationEvents(groupId, name, minPrice, maxPrice, tag); // Gán sự kiện lại
            }
        };
        xhr.send();
    }

    function attachPaginationEvents(groupId, name, minPrice, maxPrice, tag) {
        document.querySelectorAll(".pagination-link").forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const page = this.getAttribute("data-page");
                loadFilteredProducts(groupId, name, minPrice, maxPrice, tag, page);
            });
        });
    }
});