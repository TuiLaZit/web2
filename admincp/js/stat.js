document.addEventListener("DOMContentLoaded", function () {
    const dateFrom = document.getElementById("dateFrom");
    const dateTo = document.getElementById("dateTo");
    const sortOrder = document.getElementById("sortOrder");
    const searchStat = document.getElementById("searchStat");
    const listStat = document.getElementById("liststat");

    dateTo.value = new Date().toISOString().split("T")[0];

    searchStat.addEventListener("click", function () {
        const fromDate = dateFrom.value;
        const toDate = dateTo.value;
        const order = sortOrder.value; // Lấy giá trị của sortOrder

        if (fromDate > toDate) {
            alert("Ngày đến phải sau ngày bắt đầu!");
            return;
        }

        // Gửi request AJAX có kèm theo sortOrder
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../admincp/js/ajax/getStatistics.php?datefrom=${encodeURIComponent(fromDate)}&dateto=${encodeURIComponent(toDate)}&sortOrder=${order}`, true);
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        listStat.innerHTML = response.statHTML;
                    } catch (error) {
                        console.error("Lỗi xử lý phản hồi JSON:", error);
                        listStat.innerHTML = "<p>Có lỗi khi xử lý dữ liệu.</p>";
                    }
                } else {
                    console.error("Lỗi kết nối AJAX:", xhr.status);
                    listStat.innerHTML = "<p>Lỗi kết nối đến máy chủ.</p>";
                }
            }
        };

        xhr.send();
    });
});