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

    

    // Thêm phương thức để lấy trang hiện tại
    public function getCurrentPage() {
        return $this->currentPage;
    }
    public function getTotalPages() {
        return $this->totalPages;
    }

    public function getPaginationLinks() {
        $links = '';
        $params = '';
    
        // Check for search parameters and add them to the URL
        if (!empty($_GET['drinkName'])) {
            $params .= '&drinkName=' . urlencode($_GET['drinkName']);
        }
        if (!empty($_GET['category'])) {
            $params .= '&category=' . urlencode($_GET['category']);
        }
        if (!empty($_GET['min_price'])) {
            $params .= '&min_price=' . urlencode($_GET['min_price']);
        }
        if (!empty($_GET['max_price'])) {
            $params .= '&max_price=' . urlencode($_GET['max_price']);
        }
    
        for ($i = 1; $i <= $this->getTotalPages(); $i++) {
            if ($i == $this->getCurrentPage()) {
                $links .= "<strong>$i</strong>";
            } else {
                $links .= "<a href='?page=$i$params'>$i</a>";
            }
        }
        return $links;
    }
    
}
?>
