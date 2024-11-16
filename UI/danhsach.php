<?php
include("includes/session_user.php");

// Get User ID and User Category ID
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;
$usercategoriesID = null;

if ($userID) {
    // Fetch the user's category ID
    $queryCategory = "SELECT uc.UserCategoryID FROM users u 
                      JOIN usercategories uc ON u.UserCategoryID = uc.UserCategoryID
                      WHERE u.UserID = ?";
    $stmt = $conn->prepare($queryCategory);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $resultCategory = $stmt->get_result();
    $userCategory = $resultCategory->fetch_assoc();
    $usercategoriesID = $userCategory['UserCategoryID'];
}

// Fetch shift report data
$model = [];
$queryReports = "SELECT sr.ShiftReportID, sr.LastTime, u.FullName 
                 FROM ShiftReports sr 
                 JOIN users u ON sr.UserID = u.UserID
                 ORDER BY sr.LastTime DESC"; // Adjust as necessary
$stmtReports = $conn->prepare($queryReports);
$stmtReports->execute();
$resultReports = $stmtReports->get_result();

while ($row = $resultReports->fetch_assoc()) {
    $model[] = $row; // Add each report to the model array
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách báo cáo kết ca</title>
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
                <div>
                    <button class="btn btn-danger" id="btnDeleteAll">
                        <i class="ti-trash"></i>
                    </button>
                </div>
            </div>

            <form method="post" action="">
                <div class="d-flex align-items-center mb-2 w-75">
                    <label>Ngày kết ca:</label>
                    <input class="form-control w-50 ml-2" type="date" name="createDate">
                    <button class="btn btn-info ml-2" type="submit">
                        Tìm kiếm
                    </button>
                    <a href="your-url-to-refresh" class="btn btn-secondary ml-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                </div>
            </form>

            <div>
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
                            <th style="vertical-align: middle;">#</th>
                            <th style="vertical-align: middle;">Mã báo cáo kết ca</th>
                            <th style="vertical-align: middle;">Nhân viên</th>
                            <th style="vertical-align: middle;">Thời gian kết ca</th>
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
                                        <?php if ($usercategoriesID == 1): ?>
                                            <a href="#" style="text-decoration: none" class="btnDelete">
                                                <i class="mdi mdi-delete" style="font-size: 25px; color: red"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
</div>
    

    <script>
        function checkDeleteButtonVisibility() {
            var anyChecked = $('.cbkItem:checked').length > 0 || $('#SelectAll').prop('checked');
            $('#btnDeleteAll').toggle(anyChecked);
        }

        $(document).ready(function () {
            checkDeleteButtonVisibility();
            
            $('body').on('change', '.cbkItem', function () {
                checkDeleteButtonVisibility();
            });

            $('body').on('click', '#btnDeleteAll', function (e) {
                e.preventDefault();
                var str = "";
                var checkbox = $(this).parents('.card-body').find('td input:checkbox');
                var i = 0;
                checkbox.each(function () {
                    if (this.checked) {
                        var _id = $(this).val();
                        if (i == 0) {
                            str += _id;
                        } else {
                            str += "," + _id;
                        }
                        i++;
                    }
                });

                if (str.length > 0) {
                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa các bản ghi này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/ShiftReports/DeleteAll',
                                type: 'POST',
                                data: { ids: str },
                                success: function (rs) {
                                    if (rs.success) {
                                        location.reload();
                                    }
                                }
                            });
                        }
                    });
                }
            });

            $('body').on('change', '#SelectAll', function () {
                var checkStatus = this.checked;
                var checkbox = $(this).parents('.card-body').find('td input:checkbox');
                checkbox.prop('checked', checkStatus);
                checkDeleteButtonVisibility();
            });

            $('body').on('click', '.btnDelete', function (e) {
                e.preventDefault();
                var itemId = $(this).closest('tr').find('.cbkItem').val();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/ShiftReports/Delete',
                            type: 'POST',
                            data: { id: itemId },
                            success: function (rs) {
                                if (rs.success) {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>