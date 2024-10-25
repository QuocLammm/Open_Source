<?php
session_start();
include'includes/connectSQL.php';
$stmt = $conn->prepare("
    SELECT uc.UserCategoryName 
    FROM users u 
    JOIN usercategories uc ON u.UserCategoryID = uc.UserCategoryID 
    WHERE u.UserID = ?
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Lấy dữ liệu người dùng dưới dạng mảng kết hợp


// Fetch the tables data from the database
$query = "SELECT * FROM tables";
$tables = mysqli_query($conn, $query);
if (isset($_POST['tableCount'])) {
  $tableCount = intval($_POST['tableCount']);

  if ($tableCount > 0) {
      // Xóa toàn bộ các bàn hiện tại
      $deleteQuery = "DELETE FROM tables";
      mysqli_query($conn, $deleteQuery);

      // Tạo lại số lượng bàn mới
      for ($i = 1; $i <= $tableCount; $i++) {
          $tableName = "Bàn " . $i;
          $status = false; // Ban đầu, tất cả các bàn đều trống (false)
          
          // Chèn bàn mới vào cơ sở dữ liệu
          $insertQuery = "INSERT INTO tables (TableName, Status) VALUES (?, ?)";
          $stmt = $conn->prepare($insertQuery);
          $stmt->bind_param("si", $tableName, $status); // `si` tương ứng với string và integer
          $stmt->execute();
      }

      // Sau khi hoàn tất, chuyển hướng về trang Index
      header("Location: dashboard.php");
      exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danh sách bàn</title>
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-8 mx-auto"> <!-- Đã thay đổi từ col-lg-4 sang col-lg-8 -->
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <!-- For Quản lý -->                  
                            <form id="tableForm" method="post">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="d-flex align-items-center mr-3 w-100"> <!-- Đã thay đổi w-50 sang w-100 -->
                                        <label class="w-25" >Số bàn tối đa:</label>
                                        <input class="form-control" type="text" placeholder="Số bàn tối đa" name="tableCount">
                                    </div>
                                    <button class="btn btn-info btn-icon-text" id="saveButton" type="button">
                                        <i class="ti-file btn-icon-prepend"></i>Lưu
                                    </button>
                                </div>
                            </form>

                            <!-- Table display -->
                            <div class="row">
                                <?php while($item = mysqli_fetch_assoc($tables)): ?>
                                    <?php
                                    $slot = ($item['Status'] == true) ? "Có người" : "Trống";
                                    $color = ($item['Status'] == true) ? "bg-success" : "bg-info";
                                    ?>
                                    <div class="col-3 mb-3"> <!-- Đã thay đổi từ col-2 sang col-3 -->
                                        <a class="btn square-card card <?= $color ?> text-white" href="order.php?id=<?= $item['TableID'] ?>">
                                            <div class="card-body square-card-content">
                                                <p style="font-size: 30px"><?= $item['TableName'] ?></p>
                                                <p><?= $slot ?></p>
                                                <?php if ($item['Status'] == true && !empty($item['Bills'])): ?>
                                                    <p><?= number_format($item['Bills'][0]['TotalAmount'], 0, ',', '.') ?> ₫</p>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>

                            <!-- Additional logic -->
                            <?php
                            $check_query = "SELECT COUNT(*) as count FROM tables WHERE Status = true";
                            $check_result = mysqli_query($conn, $check_query);
                            $check = mysqli_fetch_assoc($check_result)['count'];
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var showAmountModal = urlParams.get('showAmountModal');
            if (showAmountModal === "True") {
                $('#amountModal').modal('show');
            }

            $('#amountModal').on('hide.bs.modal', function(e) {
                if (document.getElementById("firstAmount").classList.contains("is-invalid")) {
                    e.preventDefault();
                }
            });
        });

        function submitAmount() {
            var amountStr = document.getElementById("firstAmount").value;
            var amount = parseFloat(amountStr.replace(",", "."));
            if (isNaN(amount) || amountStr.trim() === "" || amount <= 0) {
                document.getElementById("firstAmount").classList.add("is-invalid");
            } else {
                document.getElementById("firstAmount").classList.remove("is-invalid");
                document.getElementById('amountForm').submit();
                $('#amountModal').modal('hide');
            }
        }

        document.getElementById('saveButton').addEventListener('click', function() {
            if (<?= $check ?> > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thực hiện được',
                    text: 'Có bàn đang hoạt động',
                    confirmButtonText: 'OK'
                });
            } else {
                document.getElementById('tableForm').submit();
            }
        });
    </script>
</body>
</html>
