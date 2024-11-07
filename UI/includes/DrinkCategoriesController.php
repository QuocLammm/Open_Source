<?php
include("includes/connectSQL.php"); // Kết nối đến cơ sở dữ liệu

class DrinkCategoriesController
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection; // Kết nối đến cơ sở dữ liệu
    }

    public function index()
    {
        // Lấy danh sách tất cả các loại đồ uống
        $result = $this->conn->query("SELECT * FROM DrinkCategories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function create($drinkCategory)
    {
        $stmt = $this->conn->prepare("INSERT INTO DrinkCategories (DrinkCategoryName, DrinkCategoryDescription) VALUES (?, ?)");
        $stmt->bind_param("ss", $drinkCategory['DrinkCategoryName'], $drinkCategory['DrinkCategoryDescription']);
        return $stmt->execute(); // Trả về true nếu thêm thành công
    }

    public function update($drinkCategoryId, $drinkName, $drinkDescription)
    {
        $update_sql = "UPDATE drinkcategories SET DrinkCategoryName = ?, DrinkCategoryDescription = ? WHERE DrinkCategoryID = ?";
        $stmt = $this->conn->prepare($update_sql);
        $stmt->bind_param("ssi", $drinkName, $drinkDescription, $drinkCategoryId);

        return $stmt->execute(); // Trả về kết quả thực thi
    }

    public function search($drinkCategoryName)
    {
        if (empty($drinkCategoryName)) {
            return $this->index(); // Trả về tất cả nếu không có từ khóa tìm kiếm
        }
        $query = "SELECT * FROM DrinkCategories WHERE LOWER(DrinkCategoryName) LIKE LOWER(?)";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$drinkCategoryName%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Trả về kết quả tìm kiếm
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM DrinkCategories WHERE DrinkCategoryID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute(); // Trả về kết quả xóa
    }

    public function deleteAll($ids)
    {
        $idsArray = explode(',', $ids);
        $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
        $stmt = $this->conn->prepare("DELETE FROM DrinkCategories WHERE DrinkCategoryID IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($idsArray)), ...$idsArray);
        return $stmt->execute(); // Trả về kết quả xóa nhiều
    }
}
?>