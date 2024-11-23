<?php
include("includes/session_user.php");

// Khởi tạo biến tìm kiếm
$searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$searchPhone = isset($_GET['search_phone']) ? $_GET['search_phone'] : '';

// Truy vấn dữ liệu khách hàng
$sql = "SELECT * FROM customer WHERE 1";  // Khởi tạo câu lệnh cơ bản

// Thêm điều kiện tìm kiếm nếu có
if (!empty($searchName)) {
    $sql .= " AND CustomerName LIKE ?";
}
if (!empty($searchPhone)) {
    $sql .= " AND PhoneNumber LIKE ?";
}

$stmt = $conn->prepare($sql);

// Bind tham số cho câu truy vấn
if (!empty($searchName) && !empty($searchPhone)) {
    $searchName = "%$searchName%";  // Tìm kiếm tên có chứa từ khóa
    $searchPhone = "%$searchPhone%";  // Tìm kiếm số điện thoại có chứa từ khóa
    $stmt->bind_param("ss", $searchName, $searchPhone); // Nếu cả hai tham số có giá trị
} elseif (!empty($searchName)) {
    $searchName = "%$searchName%";  // Tìm kiếm tên có chứa từ khóa
    $stmt->bind_param("s", $searchName); // Nếu chỉ tìm kiếm theo tên
} elseif (!empty($searchPhone)) {
    $searchPhone = "%$searchPhone%";  // Tìm kiếm số điện thoại có chứa từ khóa
    $stmt->bind_param("s", $searchPhone); // Nếu chỉ tìm kiếm theo số điện thoại
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Khách Hàng Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
</head>
    <style>
        .container {
            max-width: 100%;
            margin-top: 20px;
        }
        .form-section {
            width: 115%;
            padding: 10px;
            margin-top: 40px;
            margin-left: -15px;
        }
        .form-section-1 {
            width: 90%;
            padding: 10px;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .row > div {
            padding: 0 10px;
        }
        /* Cải tiến CSS để các ô tìm kiếm nằm cùng một hàng */
        .search-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;  /* Khoảng cách giữa phần tìm kiếm và bảng */
        }
        .search-row .col-md-5 {
            flex: 1;
            max-width: 300px;
        }
        .search-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-buttons .btn {
            margin-top: 10px;  /* Đảm bảo các nút căn đều với ô nhập */
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container">
        <form action="" method="GET" class="form-section">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="card-title">Danh sách khách hàng</p>
                        <a href="create_drink.php" class="btn btn-success">Thêm</a>
                    </div>
                    <form method="get" action="">
                        <div class="d-flex align-items-center mb-2 w-75">
                            <!-- Tìm kiếm theo tên khách hàng -->
                            <label for="search_name" class="mr-2">Tên khách hàng:</label>
                            <input class="form-control w-25" type="text" name="search_name" id="search_name" value="<?= isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>">

                            <!-- Tìm kiếm theo số điện thoại -->
                            <label for="search_phone" class="ml-3 mr-2">Số điện thoại:</label>
                            <input class="form-control w-25" type="text" name="search_phone" id="search_phone" value="<?= isset($_GET['search_phone']) ? htmlspecialchars($_GET['search_phone']) : '' ?>">

                            <!-- Nút tìm kiếm -->
                            <button class="btn btn-info ml-2" type="submit">
                                Tìm kiếm
                            </button>
                            <a href="index_customer.php" class="btn btn-secondary ml-2">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </form>
            <!-- Bảng dữ liệu khách hàng -->
        <div class="form-section-1">
            <table class="table table-striped">
                <thead>
                    <tr style="background-color: dodgerblue; color: white;">
                        <th>#</th>
                        <th>Mã khách hàng</th>
                        <th>Khách Hàng</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Ưu đãi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows == 0): ?>
                        <tr>
                            <td colspan="9" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($item = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $item['CustomerID'] ?></td>
                                <td><?= htmlspecialchars($item['CustomerID']) ?></td>
                                <td><?= htmlspecialchars($item['CustomerName']) ?></td>
                                <td><?= htmlspecialchars($item['Gender']) ?></td>
                                <td><?= htmlspecialchars($item['Address']) ?></td>
                                <td><?= htmlspecialchars($item['PhoneNumber']) ?></td>
                                <td><?= htmlspecialchars($item['Email']) ?></td>
                                <td><?= htmlspecialchars($item['Offer']) ?></td>
                                <td>
                                    <a href="edit_customer.php?id=<?= $item['CustomerID'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="delete_customer.php?id=<?= $item['CustomerID'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                                    <a href="send_mail.php?id=<?= $item['CustomerID'] ?>" class="btn btn-primary btn-sm">Mail</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
