<?php 
include("includes/connectSQL.php");
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;
$usercategoriesID = null;

if ($userID) {
    // Fetch the user's category IDs
    $queryCategory = " SELECT users.FullName, users.UserImage, usercategories.UserCategoryName
                        FROM users
                        JOIN usercategories ON users.UsercategoryID = usercategories.UsercategoryID
                        WHERE users.UserID = ?";
    $stmt = $conn->prepare($queryCategory);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $resultCategory = $stmt->get_result();
    $userCategory = $resultCategory->fetch_assoc();
    $usercategoriesID = $userCategory['UserCategoryName'];
    // Array of roles to check against
    $validRoles = ["Quản Lý", "Thu Ngân"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../UI/vendors/feather/feather.css">
    <link rel="stylesheet" href="../UI/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../UI/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../UI/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../UI/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="../UI/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../UI/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../UI/images/favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Ngăn cuộn ngang */
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 180px;
            background-color: #23242a;
            border-right: 1px solid #ddd;
            padding-top: 20px;
            z-index: 1000; /* Sidebar có z-index thấp hơn header */
        }
        .sidebar .logo-image {
            width: 60px;
            height: auto;
        }

        .sidebar .logo {
            text-align: center;
            padding: 10px 0;
        }

        .sidebar .logo-image {
            width: 60px;
            height: auto;
        }

        .sidebar .brand-name {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .main-header {
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            background-color: #23242a;
            color: white;
            padding: 10px;
            z-index: 1050; /* Đảm bảo header luôn nằm trên cùng */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            justify-content: space-between; /* Phân bố không gian giữa các phần tử con */
            align-items: center; /* Căn giữa các phần tử con theo chiều dọc */
            width: 100%; /* Đảm bảo phần tử này chiếm toàn bộ chiều rộng */
        }

        .user-info h4 {
            flex: 1; /* Chiếm toàn bộ không gian còn lại */
            text-align: center; /* Căn giữa nội dung */
        }

        .user-info span, .user-info img {
            margin-left: 10px; /* Khoảng cách giữa span và img */
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .logout-btn {
            color: #45f3ff; /* Màu cho nút đăng xuất */
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            background-color: #333;
            border-radius: 5px;
            
        }

        .main-content {
            margin-left: 180px; /* Điều chỉnh để tránh sidebar che khuất */
            margin-top: 60px; /* Điều chỉnh cho header không bị che khuất */
            padding: 20px;
        }
        
    </style>
</head>
<body>
<div class="sidebar">
    <!-- Sidebar navigation items -->
    <nav class="nav flex-column">
        <?php 
            if (in_array($usercategoriesID, ["Quản Lý"])): ?>
            <a class="nav-link" href="index_admin.php" style="color: #1cc88a;">Dashboard</a>
        <?php endif; ?>
        <?php 
            if (in_array(strtolower($usercategoriesID), array_map('strtolower', $validRoles))): ?>
            <a class="nav-link" href="dashboard.php" sty>Bán hàng</a>
        <?php endif; ?>
        <?php if (in_array($usercategoriesID, ["Quản Lý"])): ?>
            <a class="nav-link dropdown-toggle" id="drinkDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Đồ uống</a>
            <ul class="dropdown-menu" aria-labelledby="drinkDropdown">
                <li><a class="dropdown-item" href="index_drink.php">Đồ uống</a></li>
                <li><a class="dropdown-item" href="index_drinkcategories.php">Loại đồ uống</a></li>
            </ul>
        <?php endif; ?>

        <?php if (in_array(strtolower($usercategoriesID), array_map('strtolower', $validRoles))): ?>
            <a class="nav-link" href="index_bills.php">Hóa đơn</a>
        <?php endif; ?>

        <?php if (in_array($usercategoriesID, ["Thu ngân"])): ?>
            <a class="nav-link" href="index_shaf.php">Kết ca</a>
        <?php endif; ?>

        <?php if (in_array($usercategoriesID, ["Quản Lý"])): ?>
            <a class="nav-link" href="danhsach.php">Báo cáo kết ca</a>
            <a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Người dùng</a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="index_users.php">Người dùng</a></li>
                <li><a class="dropdown-item" href="index_usercategories.php">Loại người dùng</a></li>
                <li><a class="dropdown-item" href="index_customer.php">Khách hàng</a></li>
            </ul>
            <a class="nav-link" href="index_authorizations.php">Phân quyền</a>
        <?php endif; ?>
        
    </nav>
</div>

<div class="main-header">
    <!-- Greeting and user actions -->
    <?php if ($userID): ?>
        <div class="user-info">
            <div class="logo">
                <img src="images/lt.jpg" alt="logo" class="logo-image">
                <span class="brand-name" style="color: #1cc88a;">L&T Coffee</span>
            </div>
            <h4>Chào mừng đến với hệ thống!</h4>
            <span>Xin chào, <?= htmlspecialchars($userCategory['FullName']) ?></span>
            <!-- Link to profile page when clicking on the avatar -->
            <a href="profile.php">
                <img src="images/users/<?= !empty($userCategory['UserImage']) ? htmlspecialchars($userCategory['UserImage']) : 'avatar.jpg' ?>" alt="profile">
            </a>
            <a class="logout-btn" href="login.php">Đăng xuất</a>
        </div>
    <?php endif; ?>
</div>



<script src="../UI/vendors/js/vendor.bundle.base.js"></script>
<script src="../UI/vendors/js/vendor.bundle.base.js"></script>
<script src="../UI/vendors/chart.js/Chart.min.js"></script>
<script src="../UI/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../UI/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../UI/js/dataTables.select.min.js"></script>
<script src="../UI/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="../UI/vendors/select2/select2.min.js"></script>
<script src="../UI/js/off-canvas.js"></script>
<script src="../UI/js/hoverable-collapse.js"></script>
<script src="../UI/js/template.js"></script>
<script src="../UI/js/settings.js"></script>
<script src="../UI/js/todolist.js"></script>
<script src="../UI/js/file-upload.js"></script>
<script src="../UI/js/dashboard.js"></script>
<script src="../UI/js/Chart.roundedBarCharts.js"></script>
<script src="../UI/js/typeahead.js"></script>
<script src="../UI/js/select2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>

