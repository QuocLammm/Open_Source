<?php
// Establish the connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlcoffee"; // Replace with your database name
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy UserID từ URL
if (isset($_GET['UserID']) && is_numeric($_GET['UserID'])) {
    $userID = $_GET['UserID'];

    // Thực hiện truy vấn để lấy thông tin của người dùng
    $query = "SELECT FullName, UserCategoryID, Gender, PhoneNumber, Username, UserImage FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($fullName, $userCategoryID, $gender, $phoneNumber, $username, $userImage);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "No UserID provided!";
    exit();
}

// Xử lý form submit để lưu thông tin sau khi chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedName = $_POST['full_name']; // Ensure field names match
    $updatedUserCategoryID = $_POST['user_category']; // Ensure field names match
    $updatedGender = $_POST['gender'];
    $updatedPhoneNumber = $_POST['phone_number']; // Ensure field names match
    $updatedUsername = $_POST['username']; // Ensure field names match

    // Cập nhật thông tin người dùng
    $updateQuery = "UPDATE users SET FullName = ?, UserCategoryID = ?, Gender = ?, PhoneNumber = ?, Username = ? WHERE UserID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sisssi", $updatedName, $updatedUserCategoryID, $updatedGender, $updatedPhoneNumber, $updatedUsername, $userID);
    
    if ($updateStmt->execute()) {
        echo "<script>alert('Record updated successfully'); window.location.href='index_users.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $updateStmt->close();
}
$conn->close();
?>

<!-- Trang hiển thị form chỉnh sửa -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Đảm bảo không có tràn */
        body {
            overflow-x: hidden;
        }
        .container {
            max-width: 600px; /* Giới hạn chiều rộng container */
        }
    </style>
</head>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-5">
        <h1 class="mb-4">Chỉnh sửa người dùng</h1>
        <form method="post">
            <div class="mb-3">
                <label for="full_name" class="form-label">Họ và tên:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>" required>
            </div>
            <div class="mb-3">
                <label for="user_category" class="form-label">Loại người dùng:</label>
                <select class="form-select" id="user_category" name="user_category" required>
                    <?php
                    // Fetch user categories for dropdown
                    $categoryQuery = "SELECT UserCategoryID, UserCategoryName FROM UserCategories";
                    $categoryStmt = $conn->query($categoryQuery);
                    while ($row = $categoryStmt->fetch_assoc()) {
                        $selected = ($row['UserCategoryID'] == $userCategoryID) ? 'selected' : '';
                        echo "<option value='" . $row['UserCategoryID'] . "' $selected>" . htmlspecialchars($row['UserCategoryName']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính:</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="Nam" <?php echo ($gender == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo ($gender == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Tên tài khoản:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="index_users.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
