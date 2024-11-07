<?php
include("includes/connectSQL.php"); // Kết nối đến cơ sở dữ liệu

class UserCategoriesController
{
    public function index()
    {
        global $conn;

        // Lấy danh sách loại người dùng
        $result = $conn->query("SELECT * FROM UserCategories");
        $userCategories = $result->fetch_all(MYSQLI_ASSOC);
        
        return $userCategories;
    }

    public function search($userCategoryName)
    {
        global $conn;
        if (empty($userCategoryName)) {
            return []; // Trả về mảng rỗng nếu không có từ khóa tìm kiếm
        }
        $query = "SELECT * FROM UserCategories WHERE LOWER(UserCategoryName) LIKE LOWER(?)";
        $stmt = $conn->prepare($query);
        $searchTerm = "%$userCategoryName%";
        $stmt->bind_param("s", $searchTerm);
        if (!$stmt->execute()) {
            error_log("SQL Error: " . $stmt->error); // Ghi log nếu có lỗi
            return [];
        }
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($userCategory)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO UserCategories (UserCategoryName) VALUES (?)");
        $stmt->bind_param("s", $userCategory['UserCategoryName']);
        return $stmt->execute();
    }

    public function getCategory($id)
    {
        global $conn;

        $query = "SELECT UserCategoryName, UserCategoryDescription FROM UserCategories WHERE UserCategoryID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($userCategoryName, $userCategoryDescription);
        $stmt->fetch();
        $stmt->close();

        return ['name' => $userCategoryName, 'description' => $userCategoryDescription];
    }

    public function updateCategory($id, $name, $description)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE UserCategories SET UserCategoryName = ?, UserCategoryDescription = ? WHERE UserCategoryID = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM UserCategories WHERE UserCategoryID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function deleteAll($ids)
    {
        global $conn;

        $idsArray = explode(',', $ids);
        $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
        $stmt = $conn->prepare("DELETE FROM UserCategories WHERE UserCategoryID IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($idsArray)), ...$idsArray);
        return $stmt->execute();
    }
}
?>