<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@ViewBag.Title</title>
    <link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- plugins:css -->
    <link rel="stylesheet" href="../UI/vendors/feather/feather.css">
    <link rel="stylesheet" href="../UI/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../UI/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../UI/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../UI/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../UI/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../UI/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../UI/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../UI/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../UI/images/favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    .nav-link { color: #000; }
    .table td, .table th { vertical-align: middle; }
    .mdi-pencil, .mdi-delete { font-size: 1.2em; cursor: pointer; }
    .mdi-pencil { color: #4CAF50; }
    .mdi-delete { color: #F44336; }
    .sidebar {
        background: #f8f9fa;
        height: 100vh;
        position: fixed;
        min-height: 100vh;
        width: 200px;
    }
    .main-content {
        margin-left: 220px; /* Width of sidebar */
        padding: 20px;
    }
    .content { padding: 20px; } /* Add padding to content */
</style>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <nav class="nav flex-column">
                <!-- Logo -->
                <div class="text-center py-4">
                    <img src="../UI/images/lt2p_mini.png" alt="Logo" style="width: 100px;">
                </div>
            
                <a class="nav-link" href="#">Bán hàng</a>
                <!-- Dropdown for Người dùng -->
                <a class="nav-link dropdown-toggle" id="drinkDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Đồ uống</a>
                <ul class="dropdown-menu" aria-labelledby="drinkDropdown">
                    <li><a class="dropdown-item" href="index_drink.php">Đồ uống</a></li>
                    <li><a class="dropdown-item" href="index_drinkcategories.php">Loại đồ uống</a></li>
                </ul>
                <a class="nav-link" href="#">Hóa đơn</a>
                <a class="nav-link" href="#">Báo cáo kết ca</a>

                <!-- Dropdown for Người dùng -->
                <a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Người dùng</a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="index_users.php">Người dùng</a></li>
                    <li><a class="dropdown-item" href="index_usercategories.php">Loại người dùng</a></li>
                </ul>
                <a class="nav-link" href="#">Phân quyền</a>
            </nav>
        </div>
        
    </div>
</div>

<script>
function editUser(id) {
    // Logic for editing user
}

function deleteUser(id) {
    // Logic for deleting user
}
</script>
<!-- plugins:js -->
<script src="../UI/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../UI/vendors/chart.js/Chart.min.js"></script>
<script src="../UI/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../UI/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../UI/js/dataTables.select.min.js"></script>
<script src="../UI/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="../UI/vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../UI/js/off-canvas.js"></script>
<script src="../UI/js/hoverable-collapse.js"></script>
<script src="../UI/js/template.js"></script>
<script src="../UI/js/settings.js"></script>
<script src="../UI/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../UI/js/file-upload.js"></script>
<script src="../UI/js/dashboard.js"></script>
<script src="../UI/js/Chart.roundedBarCharts.js"></script>
<script src="../UI/js/typeahead.js"></script>
<script src="../UI/js/select2.js"></script>
<!-- End custom js for this page-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
