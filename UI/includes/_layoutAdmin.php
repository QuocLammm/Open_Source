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
    <link rel="stylesheet" type="text/css" href="../UI/js/select.dataTables.min.css">
    <link rel="stylesheet" href="../UI/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../UI/images/favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .nav-link { color: #000; }
        .table td, .table th { vertical-align: middle; }
        .mdi-pencil, .mdi-delete { font-size: 1.2em; cursor: pointer; }
        .mdi-pencil { color: #4CAF50; }
        .mdi-delete { color: #F44336; }
        
        .sidebar {
            
            height: 100vh;
            position: fixed;
            width: 220px; /* Fixed width for sidebar */
            padding-top: 56px; /* Ensure the content starts below the navbar */
        }
        .main-content {
            margin-left: 220px; /* Same as sidebar width */
            padding: 20px;
        }
        .content { padding: 20px; }
    </style>
</head>
<body>
<div class="container-fluid">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <nav class="nav flex-column">
                <!-- Logo -->
                <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                        <div class="navbar-brand brand-logo h-100">
                            <img src="images/lt.jpg" alt="logo" class="h-100" />
                        </div>
                    </div>
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="background-color: dodgerblue; color: white">
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                            <span class="icon-menu"></span>
                        </button>

                        <?php if ($user) { ?>
                            <ul class="navbar-nav navbar-nav-right">
                            
                                <li class="nav-item" id="greeting">
                                    <?= $message ?> <?php echo htmlspecialchars($user['FullName']); ?>
                                </li>
                                <li class="nav-item nav-profile dropdown">
                                    <?php if (!empty($user['UserImage'])) { ?>
                                        <img src="images/users/<?php echo htmlspecialchars($user['UserImage']); ?>" alt="profile" />
                                    <?php } else { ?>
                                        <img src="images/avatar.jpg" alt="profile" />
                                    <?php } ?>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-danger" href="login.php">Đăng xuất</a>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </nav>
                <!--Xử lí người quản lý hoặc thu ngân-->
                <?php if (in_array(strtolower($usercategoriesID), array_map('strtolower', $validRoles))): ?>
                    <a class="nav-link" href="dashboard.php">Bán hàng</a>
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
</div>

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

