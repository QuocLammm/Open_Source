<?php
include("includes/connectSQL.php");

// Xóa hóa đơn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id']; // Chuyển đổi về kiểu int để bảo mật
    $mysqli->query("DELETE FROM Bills WHERE BillID = $id");
    header("Location: index.php");
    exit();
}

// Xóa nhiều hóa đơn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    $idArray = explode(',', $ids);
    $idList = implode(',', array_map('intval', $idArray)); // Bảo mật đầu vào
    $mysqli->query("DELETE FROM Bills WHERE BillID IN ($idList)");
    header("Location: index_bills.php");
    exit();
}

// Lấy danh sách hóa đơn
$result = $conn->query("SELECT * FROM Bills");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hóa đơn</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include("includes/_layoutAdmin.php");?>
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
                        <th>#</th>
                        <th>Mã hóa đơn</th>
                        <th>Họ và tên</th>
                        <th>Ngày Lập</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input cbkItem" value="<?= htmlspecialchars($row['BillID']) ?>">
                                        </label>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['BillID']) ?></td>
                                <td><?= htmlspecialchars($row['UserFullName']) ?></td>
                                <td><?= htmlspecialchars($row['CreateDate']) ?></td>
                                <td>
                                    <button class="btnDelete" style="border:none; background:none; cursor:pointer;">
                                        <i class="mdi mdi-delete" style="font-size: 25px; color: red"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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
            $('.cbkItem:checked').each(function () {
                if (str.length > 0) str += ",";
                str += $(this).val();
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
                        $.post('index.php', {ids: str}, function (response) {
                            location.reload();
                        });
                    }
                });
            }
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
                    $.post('index.php', {id: itemId}, function (response) {
                        location.reload();
                    });
                }
            });
        });

        $('#SelectAll').change(function () {
            var checkStatus = this.checked;
            $('.cbkItem').prop('checked', checkStatus);
            checkDeleteButtonVisibility();
        });
    });
</script>

</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
