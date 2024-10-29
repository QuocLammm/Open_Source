<?php
include("includes/connectSQL.php"); // Kết nối đến cơ sở dữ liệu

class UsersController
{
    public function index()
    {
        global $conn;

        // Lấy danh sách người dùng
        $result = $conn->query("SELECT * FROM Users");
        $users = $result->fetch_all(MYSQLI_ASSOC);
        
        // Trả về view
        include ("index_users.php"); // Giả sử bạn có view ở đây
    }

    public function indexPost($fullName, $gender, $userCategoryName)
    {
        global $conn;

        // Tìm kiếm người dùng theo điều kiện
        $query = "SELECT * FROM Users WHERE 
                    (LOWER(FullName) LIKE LOWER(?) OR ? = '') AND
                    (LOWER(Gender) LIKE LOWER(?) OR ? = '') AND
                    (LOWER(UserCategoryName) LIKE LOWER(?) OR ? = '')";

        $stmt = $conn->prepare($query);
        $searchFullName = "%$fullName%";
        $searchGender = "%$gender%";
        $searchUserCategoryName = "%$userCategoryName%";

        $stmt->bind_param("ssssss", $searchFullName, $fullName, $searchGender, $gender, $searchUserCategoryName, $userCategoryName);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = $result->fetch_all(MYSQLI_ASSOC);

        // Trả về view
        include ("index_users.php");
    }

    public function create()
    {
        global $conn;

        // Lấy danh sách loại người dùng
        $result = $conn->query("SELECT * FROM UserCategories");
        $userCategories = $result->fetch_all(MYSQLI_ASSOC);

        include ("create_users.php"); // Giả sử bạn có view ở đây
    }

    public function createPost($user)
    {
        global $conn;

        // Kiểm tra tính hợp lệ
        if ($this->validateUser($user)) {
            // Xử lý hình ảnh
            if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
                $fileExtension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $imgName = $user['UserImage'] . '.' . $fileExtension;
                    $uploadDir = 'images/users/';
                    move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
                    $user['UserImage'] = $imgName;
                }
            }

            // Kiểm tra tên đăng nhập
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Users WHERE AccountName = ?");
            $stmt->bind_param("s", $user['AccountName']);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();

            if ($count > 0) {
                $error = "Tên đăng nhập đã tồn tại";
                include 'create_users.php'; // Trả về view với thông báo lỗi
                return;
            }

            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO Users (FullName, Gender, UserImage, AccountName) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user['FullName'], $user['Gender'], $user['UserImage'], $user['AccountName']);
            $stmt->execute();

            header("Location: users/index_users.php"); // Chuyển hướng về trang danh sách
            exit();
        }

        include ("create_users.php"); // Trả về view nếu không hợp lệ
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

    public function editPost($user)
    {
        global $conn;

        // Kiểm tra tính hợp lệ
        if ($this->validateUser($user)) {
            // Xử lý hình ảnh
            if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
                $fileExtension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $imgName = $user['UserImage'] . '.' . $fileExtension;
                    $uploadDir = 'images/users/';
                    move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $imgName);
                    $user['UserImage'] = $imgName;
                }
            }

            // Cập nhật người dùng
            $stmt = $conn->prepare("UPDATE Users SET FullName = ?, Gender = ?, UserImage = ? WHERE UserID = ?");
            $stmt->bind_param("sssi", $user['FullName'], $user['Gender'], $user['UserImage'], $user['UserID']);
            $stmt->execute();

            header("Location: index_users.php"); // Chuyển hướng về trang danh sách
            exit();
        }

        include 'edit.php'; // Trả về view nếu không hợp lệ
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

        echo json_encode(['success' => false]);
    }

    private function validateUser($user)
    {
        // Kiểm tra tính hợp lệ của người dùng
        return !empty($user['FullName']) && !empty($user['AccountName']);
    }

    public function search($fullName, $gender, $userCategoryName)
    {
        global $conn;

        // Tìm kiếm người dùng
        $query = "SELECT * FROM Users WHERE 
                    (LOWER(FullName) LIKE LOWER(?) OR ? = '') AND
                    (LOWER(Gender) LIKE LOWER(?) OR ? = '') AND
                    (LOWER(UserCategoryName) LIKE LOWER(?) OR ? = '')";

        $stmt = $conn->prepare($query);
        $searchFullName = "%$fullName%";
        $searchGender = "%$gender%";
        $searchUserCategoryName = "%$userCategoryName%";

        $stmt->bind_param("ssssss", $searchFullName, $fullName, $searchGender, $gender, $searchUserCategoryName, $userCategoryName);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>