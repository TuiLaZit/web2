document.addEventListener("DOMContentLoaded", function () {
    const searchModal = document.getElementById("searchModal");
    const openSearchButton = document.getElementById("openSearchModal");
    const closeSearchButton = document.querySelector(".close");
    const searchButton = document.getElementById("searchButton");
    const searchInput = document.getElementById("searchInput");
    const overlay = document.createElement("div");
    overlay.classList.add("overlay");
    document.body.appendChild(overlay);

    document.getElementById("openSearchModal").addEventListener("click", function () {
        searchModal.classList.add("active");
        overlay.classList.add("active");
        LoadGroup(); // Tải nhóm khi mở modal
    });

    document.querySelector(".close").addEventListener("click", function () {
        searchModal.classList.remove("active");
        overlay.classList.remove("active");
    });

    // Khi nhấn nút tìm kiếm, tải nhóm theo từ khóa
    searchButton.addEventListener("click", function () {
        LoadGroup(searchInput.value);
    });

    if(searchModal.classList.contains("active")) {
        
    }
    function LoadGroup(query = "") {
        const GroupList = document.getElementById("searchResults");
        if (!GroupList) {
            console.error("Không tìm thấy nhóm.");
            return;
        }

        fetch("./js/ajax/search_handler.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `query=${encodeURIComponent(query)}`,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.productsHtml) {
                GroupList.innerHTML = data.productsHtml;
            } else {
                console.error("Không tìm thấy nhóm.");
            }
        })
        .catch((error) => console.error("Lỗi khi tải nhóm:", error));
    }

    // Khi trang tải, hiển thị tất cả nhóm

});