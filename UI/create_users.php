<?php
    include("includes/connectSQL.php");

    if(isset($_POST["submit"])){
        $name = $_POST['name'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];
        $createdUserCategoryID = $_POST['user_category'];
        $phone = $_POST['phone'];
        $gener = $_POST['gener'];
        $accountname = $_POST['accountname'];

        // Đường dẫn thư mục lưu ảnh (thư mục này đã tồn tại)
        $target_dir = "..UI/images/users/";

        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Kiểm tra nếu file đã tồn tại
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Kiểm tra kích thước file
        if ($_FILES["image"]["size"] > 500000) { // 500KB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Chỉ cho phép các định dạng hình ảnh cụ thể
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Kiểm tra nếu không có lỗi, thì upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Upload thành công, lưu đường dẫn ảnh vào database
                $image = $target_file;

                // Câu lệnh SQL
                $q = "INSERT INTO users (FullName, Username, Password, PhoneNumber, Gender, UserImage, AccountName, UserCategoryID)
                VALUES ('$fullname','$name', '$password', '$phone', '$gener', '$image', '$accountname', '$createdUserCategoryID')";

                $query = mysqli_query($conn, $q);

                if($query){
                    header("Location: index_users.php");
                    exit();
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
?>




<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng mới</title>
    <link rel="stylesheet" href="../UI/css/bootstrap.min.css">
</head>
<style>
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
</style>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Thêm người dùng mới</h3>
            <a href="index_users.php" class="btn btn-primary mb-2">
                <i class="ti-arrow-right"></i> Quay lại
            </a>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fullname">Tên người dùng <span class="text-danger">*</span></label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Tên người dùng (Viết liền không dấu) <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gener">Giới tính <span class="text-danger">*</span></label>
                        <select id="gener" name="gener" class="form-control" required>
                            <option value="Male">Nam</option>
                            <option value="Female">Nữ</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_category">Tên loại người dùng <span class="text-danger">*</span></label>
                        <select id="user_category" name="user_category" class="form-control" required>
                            <?php
                            // Fetch user categories for dropdown
                            $categoryQuery = "SELECT UserCategoryID, UserCategoryName FROM UserCategories";
                            $categoryStmt = $conn->query($categoryQuery);
                            while ($row = $categoryStmt->fetch_assoc()) {
                                echo "<option value='" . $row['UserCategoryID'] . "'>" . htmlspecialchars($row['UserCategoryName']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh <span class="text-danger">*</span></label>
                        <input type="file" id="image" name="image" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="accountname">Tên tài khoản <span class="text-danger">*</span></label>
                        <input type="text" id="accountname" name="accountname" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../UI/js/bootstrap.bundle.min.js"></script>
</body>
</html>
