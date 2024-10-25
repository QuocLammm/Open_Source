<?php
class Pager {
    private $currentPage;
    private $totalPages;
    private $data;

    public function __construct($data, $itemsPerPage) {
        $this->data = $data;
        $this->totalPages = ceil(count($data) / $itemsPerPage);
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        } elseif ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }
    }

    public function getDataForCurrentPage($itemsPerPage) {
        $offset = ($this->currentPage - 1) * $itemsPerPage;
        return array_slice($this->data, $offset, $itemsPerPage);
    }

    public function getPaginationLinks() {
        $links = "";
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->currentPage) {
                $links .= "<strong>$i</strong> "; // Hiển thị số trang hiện tại
            } else {
                $links .= "<a href=\"?page=$i\">$i</a> "; // Hiển thị các liên kết trang khác
            }
        }
        return $links;
    }
    

    // Thêm phương thức để lấy trang hiện tại
    public function getCurrentPage() {
        return $this->currentPage;
    }
}
?>
