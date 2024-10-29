<?php
include("includes/connectSQL.php"); // Kết nối đến cơ sở dữ liệu

class UsersController
{
    public function index()
    {
        global $conn;

        // Lấy danh sách người dùng kết hợp với loại người dùng
        $query = "
            SELECT u.*, uc.UserCategoryName
            FROM Users u
            LEFT JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID
        ";

        $result = $conn->query($query);
        $users = $result->fetch_all(MYSQLI_ASSOC);
        
        return $users;
    }

    

    public function create()
    {
        global $conn;

        // Lấy danh sách loại người dùng
        $result = $conn->query("SELECT * FROM UserCategories");
        $userCategories = $result->fetch_all(MYSQLI_ASSOC);

        return $userCategories;
    }

    

    public function edit($id)
    {
        global $conn;

        // Lấy thông tin người dùng
        $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // Lấy danh sách loại người dùng
        $result = $conn->query("SELECT * FROM UserCategories");
        $userCategories = $result->fetch_all(MYSQLI_ASSOC);

        include ("edit_users.php"); // Giả sử bạn có view ở đây
    }

    

    public function delete($id)
    {
        global $conn;

        // Lấy người dùng
        $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // Xóa ảnh
        if ($user['UserImage']) {
            $filePath = 'images/users/' . $user['UserImage'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Xóa người dùng
        $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(['success' => true]);
    }

    public function deleteAll($ids)
    {
        global $conn;

        if (!empty($ids)) {
            $items = explode(',', $ids);
            foreach ($items as $item) {
                $this->delete($item); // Gọi hàm delete cho từng ID
            }
            echo json_encode(['success' => true]);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Không có ID nào để xóa.']);
    }

    private function validateUser($user)
    {
        // Kiểm tra tính hợp lệ của người dùng
        return !empty($user['FullName']) && !empty($user['AccountName']);
    }

    public function search($fullName, $gender, $userCategoryName)
    {
        global $conn;

        $query = "SELECT u.*, uc.UserCategoryName 
                FROM Users u 
                LEFT JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID 
                WHERE 1=1"; // 1=1 để dễ dàng thêm điều kiện

        // Thêm điều kiện cho tên người dùng
        if (!empty($fullName)) {
            $fullName = $conn->real_escape_string($fullName);
            $query .= " AND u.FullName LIKE '%$fullName%'";
        }

        // Thêm điều kiện cho giới tính (nếu cần)
        if (!empty($gender)) {
            $gender = $conn->real_escape_string($gender);
            $query .= " AND u.Gender = '$gender'";
        }

        // Thêm điều kiện cho tên loại người dùng
        if (!empty($userCategoryName)) {
            $userCategoryName = $conn->real_escape_string($userCategoryName);
            $query .= " AND uc.UserCategoryName LIKE '%$userCategoryName%'";
        }

        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>