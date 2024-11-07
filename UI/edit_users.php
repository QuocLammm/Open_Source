<?php
include("includes/session_user.php");

// Lấy UserID từ URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userID = $_GET['id'];

    // Thực hiện truy vấn để lấy thông tin của người dùng
    $query = "SELECT FullName, UserCategoryID, Gender, PhoneNumber, Username, UserImage, Password FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($fullName, $userCategoryID, $gender, $phoneNumber, $username, $userImage, $password);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "No UserID provided!";
    exit();
}

// Xử lý form submit để lưu thông tin sau khi chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedName = $_POST['full_name'];
    $updatedUserCategoryID = $_POST['user_category'];
    $updatedGender = $_POST['gender'];
    $updatedPhoneNumber = $_POST['phone_number'];
    $updatedUsername = $_POST['username'];
    $updatedPassword = $_POST['password'];

    // Handle profile picture upload
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
        $targetDir = "images/";
        $userImageName = basename($_FILES['user_image']['name']);
        $targetFilePath = $targetDir . $userImageName;

        if (move_uploaded_file($_FILES['user_image']['tmp_name'], $targetFilePath)) {
            // Update query with image
            $updateQuery = "UPDATE users SET FullName = ?, UserCategoryID = ?, Gender = ?, PhoneNumber = ?, Username = ?, UserImage = ?, Password = ? WHERE UserID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sisssssi", $updatedName, $updatedUserCategoryID, $updatedGender, $updatedPhoneNumber, $updatedUsername, $userImageName, $updatedPassword, $userID);
        } else {
            echo "Error uploading file.";
        }
        
    } else {
       // Update without changing the image
        $updateQuery = "UPDATE users SET FullName = ?, UserCategoryID = ?, Gender = ?, PhoneNumber = ?, Username = ?, Password = ? WHERE UserID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sissssi", $updatedName, $updatedUserCategoryID, $updatedGender, $updatedPhoneNumber, $updatedUsername, $updatedPassword, $userID);
    }

    if ($updateStmt->execute()) {
        echo "<script>alert('Record updated successfully'); window.location.href='index_users.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $updateStmt->close();
}
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
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Space between columns */
        }
        .form-column {
            flex: 1; /* Equal width columns */
        }
        .text-center {
            text-align: center;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            padding: 10px;
            margin: 70px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .btnDelete {
            cursor: pointer;
        }
        .pagination {
            display: flex;
            justify-content: center; /* Căn giữa các liên kết */
            gap: 10px; /* Tạo khoảng cách giữa các liên kết */
        }

        .pagination a {
            text-decoration: none; /* Bỏ gạch chân cho liên kết */
            padding: 8px 12px; /* Thêm padding cho các liên kết */
            border: 1px solid #007bff; /* Đường viền cho các liên kết */
            border-radius: 5px; /* Bo góc cho các liên kết */
            color: #007bff; /* Màu chữ */
        }

        .pagination a:hover {
            text-decoration: none; /* Bỏ gạch chân cho liên kết */
            background-color: #007bff; /* Màu nền khi hover */
            color: white; /* Màu chữ khi hover */
        }

        .pagination strong {
            color: red; /* Màu chữ cho trang hiện tại */
            border: 1px solid #007bff; /* Đường viền cho trang hiện tại */
            padding: 8px 12px; /* Padding tương tự như các liên kết khác */
            border-radius: 5px; /* Bo góc giống nhau */
        }
        .profile-pic {
            width: 100px; /* Set a specific width */
            height: 120px; /* Keep the aspect ratio */
            border-radius: 5px; /* Optional: Add some border radius */
        }
    </style>
</head>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container">
        <form method="post" enctype="multipart/form-data" class="form-section"> <!-- Added enctype for file uploads -->
        <h3 class="text-center mb-4">Chỉnh Sửa Người Dùng</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Tên người dùng:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_image" class="form-label">Hình ảnh:</label>
                        <input type="file" class="form-control" id="user_image" name="user_image">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giới tính:</label><br>
                        <input type="radio" id="gender_male" name="gender" value="Nam" <?php echo ($gender == 'Nam') ? 'checked' : ''; ?>>
                        <label for="gender_male">Nam</label>
                        <input type="radio" id="gender_female" name="gender" value="Nữ" <?php echo ($gender == 'Nữ') ? 'checked' : ''; ?>>
                        <label for="gender_female">Nữ</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="user_category" class="form-label">Tên loại người dùng:</label>
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
                        <label for="username" class="form-label">Tên tài khoản:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                    </div>
                </div>
            </div>

            <!-- Display current profile picture and option to change it -->
            <div class="mb-3">
                <label for="user_image" class="form-label">Ảnh đại diện:</label><br>
                <?php if (!empty($userImage)) : ?>
                    <img src="images/users/<?php echo htmlspecialchars($userImage); ?>" class="profile-pic mb-2" alt="Current Profile Picture">
                <?php else : ?>
                    <p>Chưa có ảnh đại diện</p>
                <?php endif; ?>
            </div>

            <div class="text-center btn-save">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="index_users.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</body>

</html>
