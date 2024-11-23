<?php
include("includes/session_user.php"); // Kết nối cơ sở dữ liệu

$userCategoryID = null;
$userCategoryName = '';
$userCategoryDescription = '';

// Lấy UserCategoryID từ URL và kiểm tra hợp lệ
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userCategoryID = $_GET['id'];

    // Lấy thông tin loại người dùng
    $stmt = $conn->prepare("SELECT UserCategoryName, UserCategoryDescription FROM UserCategories WHERE UserCategoryID = ?");
    $stmt->bind_param("i", $userCategoryID);
    $stmt->execute();
    $stmt->bind_result($userCategoryName, $userCategoryDescription);

    if (!$stmt->fetch()) {
        echo "Không tìm thấy loại người dùng.";
        exit();
    }
    $stmt->close();
} else {
    echo "No valid UserCategoryID provided!";
    exit();
}

// Xử lý form submit để lưu thông tin sau khi chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedName = htmlspecialchars($_POST['name']);  // Tránh XSS
    $updatedDescription = htmlspecialchars($_POST['description']);

    // Cập nhật thông tin loại người dùng
    $stmt = $conn->prepare("UPDATE UserCategories SET UserCategoryName = ?, UserCategoryDescription = ? WHERE UserCategoryID = ?");
    $stmt->bind_param("ssi", $updatedName, $updatedDescription, $userCategoryID);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='index_usercategories.php';</script>";
    } else {
        echo "Lỗi khi cập nhật bản ghi: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!-- Trang hiển thị form chỉnh sửa -->
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa loại người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
        }
        .btn-success {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <?php include('includes/_layoutAdmin.php'); ?>
    <div class="container mt-5">
        <form method="post" class="form-section">
            <h2 class="mb-4">Chỉnh sửa loại người dùng</h2>
            <div class="mb-3">
                <label for="name" class="form-label">Tên loại người dùng:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userCategoryName); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($userCategoryDescription); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="index_usercategories.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>

</html>
