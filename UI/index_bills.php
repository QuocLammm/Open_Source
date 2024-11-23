<?php
require_once("includes/session_user.php");
include("includes/Pager.php");

$itemsPerPage = 5; // Số hóa đơn trên mỗi trang

// Kiểm tra xem có yêu cầu xóa hóa đơn không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xóa một hóa đơn
    if (isset($_POST['idBill'])) {
        $idBill = intval($_POST['idBill']);

        // Bắt đầu xóa sản phẩm trong `billinfos`
        $sqlDeleteBillInfos = "DELETE FROM billinfos WHERE BillID = ?";
        $stmt = $conn->prepare($sqlDeleteBillInfos);
        $stmt->bind_param("i", $idBill);

        if ($stmt->execute()) {
            // Tiếp tục xóa hóa đơn trong `bills`
            $sqlDeleteBill = "DELETE FROM bills WHERE BillID = ?";
            $stmt = $conn->prepare($sqlDeleteBill);
            $stmt->bind_param("i", $idBill);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Xóa hóa đơn thành công."]);
            } else {
                echo json_encode(["success" => false, "message" => "Lỗi xóa hóa đơn: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi xóa sản phẩm trong hóa đơn: " . $conn->error]);
        }
        exit;
    }

    // Xóa nhiều hóa đơn
    if (isset($_POST['ids'])) {
        $ids = array_map('intval', explode(',', $_POST['ids']));

        // Xóa các sản phẩm trong `billinfos`
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sqlDeleteBillInfos = "DELETE FROM billinfos WHERE BillID IN ($placeholders)";
        $stmt = $conn->prepare($sqlDeleteBillInfos);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);

        if ($stmt->execute()) {
            // Tiếp tục xóa các hóa đơn trong `bills`
            $sqlDeleteBills = "DELETE FROM bills WHERE BillID IN ($placeholders)";
            $stmt = $conn->prepare($sqlDeleteBills);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Xóa nhiều hóa đơn thành công."]);
            } else {
                echo json_encode(["success" => false, "message" => "Lỗi xóa hóa đơn: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi xóa sản phẩm trong hóa đơn: " . $conn->error]);
        }
        exit;
    }
}

// Lấy hóa đơn và xử lý phân trang
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}
$offset = ($currentPage - 1) * $itemsPerPage;

$sql = "SELECT bills.BillID, users.FullName, bills.CreateDate 
        FROM bills 
        JOIN users ON bills.UserID = users.UserID 
        LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);
$bills = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$totalResults = $conn->query("SELECT COUNT(*) as count FROM bills")->fetch_assoc()['count'];
$pager = new Pager(range(1, $totalResults), $itemsPerPage);

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hóa đơn</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
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
    </style>
</head>
<body>
<?php include("includes/_layoutAdmin.php"); ?>
<div class="container mt-4">
    <form action="" method="POST" class="form-section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <p class="card-title">Danh sách hóa đơn</p>
                    <div>
                        <button class="btn btn-danger" id="btnDeleteAll" style="display:none;">
                            <i class="ti-trash"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr style="background-color: dodgerblue; color: white;">
                                <th>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" id="SelectAll">
                                        </label>
                                    </div>
                                </th>
                                <th>Mã hóa đơn</th>
                                <th>Họ và tên</th>
                                <th>Ngày Lập</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bills)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bills as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input cbkItem" value="<?= htmlspecialchars($row['BillID']) ?>">
                                                </label>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($row['BillID']) ?></td>
                                        <td><?= htmlspecialchars($row['FullName']) ?></td>
                                        <td><?= htmlspecialchars($row['CreateDate']) ?></td>
                                        <td style="display: flex; align-items: center;">
                                            <a href="detail_bills.php?BillID=<?= urlencode($row['BillID']) ?>" 
                                               style="text-decoration: none; padding: 10px;">
                                                <i class="mdi mdi-eye" style="font-size: 25px; color: blue;"></i>
                                            </a>
                                            <button class="action-button btnDelete" 
                                                    data-bill-id="<?= htmlspecialchars($row['BillID']) ?>" 
                                                    style="border:none; background:none; cursor:pointer; padding: 10px;">
                                                <i class="mdi mdi-delete" style="font-size: 25px; color: red;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Display pagination links -->
                <div class="pagination">
                    <?= $pager->getPaginationLinks() ?>
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

        // Show delete button when at least one item is checked
        $('body').on('change', '.cbkItem', function () {
            checkDeleteButtonVisibility();
        });

        // Handle multiple bill deletions
        $('body').on('click', '#btnDeleteAll', function (e) {
            e.preventDefault();
            var idList = [];
            $('.cbkItem:checked').each(function () {
                idList.push($(this).val());
            });

            if (idList.length > 0) {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa các bản ghi này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('index_bills.php', { ids: idList.join(',') }, function (response) {
                            console.log("Phản hồi từ server:", response);
                            location.reload();
                        });
                    }
                });
            }
        });

        // Handle single bill deletion
        $('body').on('click', '.btnDelete', function (e) {
            e.preventDefault();
            var idBill = $(this).data('bill-id'); // Get the BillID from data attribute

            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('index_bills.php', { idBill: idBill }, function (response) {
                        console.log("Phản hồi từ server:", response);
                        location.reload();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.error("Error deleting bill:", textStatus, errorThrown);
                    });
                }
            });
        });

        // Select all checkboxes
        $('#SelectAll').change(function () {
            var checkStatus = this.checked;
            $('.cbkItem').prop('checked', checkStatus);
            checkDeleteButtonVisibility();
        });
    });
</script>

</body>
</html>

<?php $conn->close(); ?>