<?php
require_once("includes/session_user.php");
include("includes/Pager.php");

$itemsPerPage = 5; // Số hóa đơn trên mỗi trang

// Lấy dữ liệu tìm kiếm từ GET (sử dụng $_GET thay vì $_POST để giữ dữ liệu khi tải lại trang)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Lấy trang hiện tại và tính toán offset
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}
$offset = ($currentPage - 1) * $itemsPerPage;

// Xây dựng câu lệnh SQL với điều kiện tìm kiếm
$sql = "SELECT bills.BillID, users.FullName, bills.CreateDate
        FROM bills
        JOIN users ON bills.UserID = users.UserID
        WHERE 1";

if (!empty($search)) {
    // Tìm kiếm theo mã hóa đơn
    $sql .= " AND bills.BillID LIKE ?";
}

if (!empty($date)) {
    // Tìm kiếm theo ngày lập hóa đơn
    $sql .= " AND DATE(bills.CreateDate) = ?";
}

$sql .= " LIMIT $itemsPerPage OFFSET $offset";

// Chuẩn bị câu lệnh và thực hiện tìm kiếm
$stmt = $conn->prepare($sql);

// Bind các tham số tìm kiếm
if (!empty($search) && !empty($date)) {
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $date);
} elseif (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bind_param("s", $searchTerm);
} elseif (!empty($date)) {
    $stmt->bind_param("s", $date);
}

$stmt->execute();

// Sử dụng `bind_result()` thay vì `get_result()`
$stmt->bind_result($billID, $fullName, $createDate);

$bills = [];
while ($stmt->fetch()) {
    $bills[] = [
        'BillID' => $billID,
        'FullName' => $fullName,
        'CreateDate' => $createDate,
    ];
}

// Lấy tổng số kết quả tìm kiếm để phân trang
$totalResultsQuery = "SELECT COUNT(*) as count FROM bills WHERE 1";
if (!empty($search)) {
    $totalResultsQuery .= " AND BillID LIKE ?";
}
if (!empty($date)) {
    $totalResultsQuery .= " AND DATE(CreateDate) = ?";
}
$stmt = $conn->prepare($totalResultsQuery);
if (!empty($search) && !empty($date)) {
    $stmt->bind_param("ss", $searchTerm, $date);
} elseif (!empty($search)) {
    $stmt->bind_param("s", $searchTerm);
} elseif (!empty($date)) {
    $stmt->bind_param("s", $date);
}
$stmt->execute();

// Lấy kết quả tổng số lượng
$stmt->bind_result($totalResults);
$stmt->fetch();

$pager = new Pager(range(1, $totalResults), $itemsPerPage);

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hóa đơn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            width: 102%;
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

        /* Điều chỉnh icon không có border */
        .btn {
            border: none; /* Bỏ border cho nút */
            background-color: transparent; /* Đảm bảo màu nền trong suốt */
            padding: 5px 10px; /* Cung cấp padding để tạo không gian cho icon */
        }

        .btn i {
            font-size: 20px; /* Kích thước icon vừa phải */
            margin-right: 5px; /* Khoảng cách giữa icon và text nếu có */
        }

        /* Giữ khoảng cách giữa các nút mà không đóng khung */
        .table td .btn {
            margin-right: 10px; /* Thêm khoảng cách giữa các nút */
        }

        /* Tạo hiệu ứng hover cho các nút */
        .btn:hover {
            background-color: #007bff; /* Thay đổi màu nền khi hover */
            color: white; /* Đổi màu chữ khi hover */
        }

        /* Điều chỉnh kích thước của biểu tượng */
        .mdi {
            font-size: 18px; /* Đảm bảo kích thước icon phù hợp */
        }


                /* CSS cho form tìm kiếm */
        form .d-flex {
            display: flex;
            flex-wrap: wrap;  /* Cho phép các phần tử nằm trên nhiều dòng khi không đủ không gian */
            gap: 10px;  /* Thêm khoảng cách giữa các phần tử */
            justify-content: flex-start;
            align-items: center;
        }

        /* Đảm bảo các label và input có khoảng cách đều */
        form .form-label {
            margin-bottom: 0;
            font-weight: 500;
        }

        form .form-control {
            max-width: 250px;  /* Đặt chiều rộng tối đa cho các input */
        }

        /* Điều chỉnh khoảng cách giữa các nút */
        form .btn {
            margin-top: 0; /* Đảm bảo nút không có khoảng cách thừa trên */
        }

        /* Thêm khoảng cách giữa các phần tử để không bị chật chội */
        form .ms-3 {
            margin-left: 1rem;  /* Thêm khoảng cách cho phần tử thứ hai trở đi */
        }

        form .w-25 {
            width: 25% !important; /* Đảm bảo input có chiều rộng linh hoạt */
        }

        /* Điều chỉnh kích thước icon */
        .mdi {
            font-size: 20px;
        }
    </style>
</head>
<body>
<?php include("includes/_layoutAdmin.php"); ?>
<div class="container mt-4">
    <form action="" method="GET" class="form-section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <p class="card-title">Danh sách hóa đơn</p>
                </div>
                
                <!-- Form tìm kiếm -->
                <div class="d-flex align-items-center mb-3 w-100">
                    <label for="createDate" class="form-label me-2">Ngày lập hóa đơn:</label>
                    <input class="form-control w-25" type="date" name="date" id="createDate" value="<?= htmlspecialchars($date) ?>">
                    
                    <label for="billID" class="form-label me-2 ms-3">Mã hóa đơn:</label>
                    <input class="form-control w-25" type="text" name="search" id="billID" value="<?= htmlspecialchars($search) ?>">
                    
                    <button class="btn btn-info ms-3" type="submit">
                        Tìm kiếm
                    </button>
                    
                    <a href="index_bills.php" class="btn btn-secondary ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                </div>

                <!-- Hiển thị danh sách hóa đơn -->
                <div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr style="background-color: dodgerblue; color: white;">
                                <th>Mã hóa đơn</th>
                                <th>Họ và tên</th>
                                <th>Ngày Lập</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bills)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bills as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['BillID']) ?></td>
                                        <td><?= htmlspecialchars($row['FullName']) ?></td>
                                        <td><?= htmlspecialchars($row['CreateDate']) ?></td>
                                        <td>
                                            <a href="detail_bills.php?BillID=<?= urlencode($row['BillID']) ?>" class="btn btn-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <button class="btn btn-danger btnDelete" data-id="<?= $row['BillID'] ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="pagination">
                    <?= $pager->getPaginationLinks() ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btnDelete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var billID = this.getAttribute('data-id'); // Lấy ID hóa đơn từ data-id của nút xóa

            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gửi yêu cầu xóa qua fetch API
                    fetch('includes/delete_bills.php', {
                        method: 'POST',
                        body: JSON.stringify({ id: billID }), // Gửi dữ liệu JSON
                        headers: { 'Content-Type': 'application/json' } // Đặt header cho dữ liệu JSON
                    })
                    .then(response => response.json()) // Nhận dữ liệu trả về từ server
                    .then(data => {
                        if (data.success) {
                            // Hiển thị thông báo thành công và làm mới trang
                            Swal.fire({
                                title: 'Xóa thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload(); // Làm mới trang để cập nhật danh sách
                            });
                        } else {
                            // Hiển thị thông báo lỗi
                            Swal.fire({
                                title: 'Lỗi!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        // Hiển thị thông báo lỗi nếu có vấn đề khi gửi yêu cầu
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Đã xảy ra lỗi khi xóa.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });
});

</script>

</body>
</html>

