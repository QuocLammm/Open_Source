<?php
require_once("includes/session_user.php");

function deleteBill($idBill) {
    global $conn;

    // Delete the bill
    $stmt = $conn->prepare("DELETE FROM Bills WHERE BillID = ?");
    $stmt->bind_param("i", $idBill);
    $stmt->execute();

    // Check if deletion was successful
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete bill."]);
    }

    $stmt->close();
}

function deleteBillItem($idDrink, $idBill) {
    global $conn;

    // Delete the item from BillInfos based on BillID and DrinkID
    $stmt = $conn->prepare("DELETE FROM BillInfos WHERE BillID = ? AND DrinkID = ?");
    $stmt->bind_param("ii", $idBill, $idDrink);
    $stmt->execute();

    // Check if there are any remaining BillInfos for this BillID
    $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM BillInfos WHERE BillID = ?");
    $checkStmt->bind_param("i", $idBill);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        // If no items left in BillInfos, delete the Bill from Bills table
        $deleteBillStmt = $conn->prepare("DELETE FROM Bills WHERE BillID = ?");
        $deleteBillStmt->bind_param("i", $idBill);
        $deleteBillStmt->execute();
    } else {
        // If there are still items, update the TotalAmount in Bills
        $updateStmt = $conn->prepare("UPDATE Bills SET TotalAmount = (SELECT SUM(DrinkCount * DrinkPrice) FROM BillInfos WHERE BillID = ?) WHERE BillID = ?");
        $updateStmt->bind_param("ii", $idBill, $idBill);
        $updateStmt->execute();
    }

    // Return JSON confirmation of success
    echo json_encode(["success" => true]);

    // Close statements
    $stmt->close();
    $checkStmt->close();
    $deleteBillStmt->close();
    $updateStmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idBill'])) {
        $idBill = $_POST['idBill'];
        deleteBill($idBill);
    } elseif (isset($_POST['idDrink']) && isset($_POST['idBill'])) {
        $idDrink = $_POST['idDrink'];
        $idBill = $_POST['idBill'];
        deleteBillItem($idDrink, $idBill);
    } elseif (isset($_POST['ids'])) {
        // Handle multiple deletion
        $ids = $_POST['ids'];
        $idArray = explode(',', $ids);
        $idList = implode(',', array_map('intval', $idArray));
        if (!$conn->query("DELETE FROM Bills WHERE BillID IN ($idList)")) {
            echo "Lỗi khi xóa nhiều hóa đơn: " . $conn->error;
        }
        header("Location: index_bills.php");
        exit();
    }
}

// Retrieve the list of bills
$stmt = $conn->prepare("SELECT bills.BillID, users.FullName, bills.CreateDate FROM bills JOIN users ON bills.UserID = users.UserID");
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "Error: " . $conn->error; // Check for SQL errors
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hóa đơn</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
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
                            <?php if ($result->num_rows === 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
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
                                <?php endwhile; ?>
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

<?php $conn->close(); ?>s