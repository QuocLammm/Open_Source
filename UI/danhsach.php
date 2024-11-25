<?php
include("includes/session_user.php");
include("includes/Pager.php");

// Kiểm tra và lấy UserID từ cookie
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;
$usercategoriesID = null;

// Lấy UserCategoryID nếu UserID tồn tại
if ($userID) {
    $queryCategory = "SELECT uc.UserCategoryID FROM users u 
                      JOIN usercategories uc ON u.UserCategoryID = uc.UserCategoryID
                      WHERE u.UserID = ?";
    $stmtCategory = $conn->prepare($queryCategory);
    $stmtCategory->bind_param("i", $userID);
    $stmtCategory->execute();
    $resultCategory = $stmtCategory->get_result();
    $userCategory = $resultCategory->fetch_assoc();
    $usercategoriesID = $userCategory['UserCategoryID'];
}

// Cài đặt phân trang
$resultsPerPage = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage; 

// Lấy dữ liệu báo cáo kết ca với phân trang
$queryReports = "SELECT sr.ShiftReportID, sr.LastTime, u.FullName 
                 FROM ShiftReports sr 
                 JOIN users u ON sr.UserID = u.UserID
                 ORDER BY sr.LastTime DESC
                 LIMIT ?, ?";
$stmtReports = $conn->prepare($queryReports);
$stmtReports->bind_param("ii", $offset, $resultsPerPage);
$stmtReports->execute();
$resultReports = $stmtReports->get_result();

$model = [];
while ($row = $resultReports->fetch_assoc()) {
    $model[] = $row; // Thêm từng báo cáo vào mảng
}

// Lấy tổng số báo cáo để phân trang
$queryTotal = "SELECT COUNT(*) FROM ShiftReports";
$stmtTotal = $conn->prepare($queryTotal);
$stmtTotal->execute();
$stmtTotal->bind_result($totalResults);
$stmtTotal->fetch();

$itemsPerPage = 7; // Số hóa đơn trên mỗi trang
$pager = new Pager(range(1, $totalResults), $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách báo cáo kết ca</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
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
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 5px;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination strong {
            color: red;
            border: 1px solid #007bff;
            padding: 8px 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container mt-4">
        <form action="" method="POST" class="form-section">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="card-title">Danh sách báo cáo kết ca</p>
                    </div>

                    <form method="post" action="">
                        <div class="d-flex align-items-center mb-2 w-75">
                            <label>Ngày kết ca:</label>
                            <input class="form-control w-50 ml-2" type="date" name="createDate">
                            <button class="btn btn-info ml-2" type="submit">Tìm kiếm</button>
                            <a href="danhsach.php" class="btn btn-secondary ml-2">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                        </div>
                    </form>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr style="background-color: dodgerblue; color: white;">
                                <?php if ($usercategoriesID == 1): ?>
                                    <th>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" id="SelectAll">
                                            </label>
                                        </div>
                                    </th>
                                <?php endif; ?>
                                <th>#</th>
                                <th>Mã báo cáo kết ca</th>
                                <th>Nhân viên</th>
                                <th>Thời gian kết ca</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($model)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php $count = 1; foreach ($model as $item): ?>
                                    <tr>
                                        <?php if ($usercategoriesID == 1): ?>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input cbkItem" value="<?= $item['ShiftReportID'] ?>">
                                                    </label>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                        <td><?= $count++ ?></td>
                                        <td><?= htmlspecialchars($item['ShiftReportID']) ?></td>
                                        <td><?= htmlspecialchars($item['FullName']) ?></td>
                                        <td><?= htmlspecialchars(date("Y-m-d H:i:s", strtotime($item['LastTime']))) ?></td>
                                        <td>
                                            <a href="detail_shiftReports.php?id=<?= $item['ShiftReportID'] ?>" style="text-decoration: none; margin-right: 20px">
                                                <i class="mdi mdi-eye" style="font-size: 25px; color: dodgerblue;"></i>
                                            </a>
                                            <a href="#" style="text-decoration: none" class="btnDelete" data-id="<?= $item['ShiftReportID'] ?>">
                                                <i class="mdi mdi-delete" style="font-size: 25px; color: red;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="pagination ">
                        <?php echo $pager->getPaginationLinks(); ?>
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
                    var billID = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Hủy',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('includes/delete_KC.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id: billID }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Xóa thành công!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: data.message || 'Không thể xóa bản ghi',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
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
