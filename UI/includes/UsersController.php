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

        // Create the base query
        $query = "SELECT u.*, uc.UserCategoryName 
                FROM Users u 
                LEFT JOIN UserCategories uc ON u.UserCategoryID = uc.UserCategoryID 
                WHERE 1=1";

        // Add search conditions dynamically based on user input
        if (!empty($fullName)) {
            $query .= " AND u.FullName LIKE ?";
        }
        if (!empty($gender)) {
            $query .= " AND u.Gender = ?";
        }
        if (!empty($userCategoryName)) {
            $query .= " AND uc.UserCategoryName LIKE ?";
        }

        // Prepare and execute the query
        $stmt = $conn->prepare($query);

        // Bind parameters based on search inputs
        $types = "";
        $params = [];

        if (!empty($fullName)) {
            $types .= "s";
            $params[] = "%" . $fullName . "%";
        }
        if (!empty($gender)) {
            $types .= "s";
            $params[] = $gender;
        }
        if (!empty($userCategoryName)) {
            $types .= "s";
            $params[] = "%" . $userCategoryName . "%";
        }

        // Bind parameters and execute
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        return $users;
        }

    }

?>