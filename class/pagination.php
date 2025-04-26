<?php
class Pagination {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;

    public function __construct($totalItems, $itemsPerPage, $currentPage) {
        $this->totalItems = min($totalItems, 12); // Giới hạn tối đa 12 sản phẩm
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
    }

    public function generatePaginationHtml() {
        $paginationHtml = '<div class="pagination">';

        // Nút lùi
        if ($this->currentPage > 1) {
            $prevPage = $this->currentPage - 1;
            $paginationHtml .= "<a href='#' class='pagination-link' data-page='$prevPage'><p>&laquo;</p></a>";
        } else {
            $paginationHtml .= "<span class='disabled-pagination'><p>&laquo;</p></span>";
        }

        // Hiển thị số trang hiện tại
        $paginationHtml .= "<span class='current-page-info'>{$this->currentPage} / {$this->totalPages}</span>";

        // Nút tiến
        if ($this->currentPage < $this->totalPages) {
            $nextPage = $this->currentPage + 1;
            $paginationHtml .= "<a href='#' class='pagination-link' data-page='$nextPage'> <p>&raquo;</p></a>";
        } else {
            $paginationHtml .= "<span class='disabled-pagination'><p> &raquo;</p></span>";
        }

        $paginationHtml .= '</div>';
        return $paginationHtml;
    }
}
?>