<?php
require_once("includes/session_user.php");
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;

// Fetch the tables data from the database
$query = "SELECT * FROM tables";
$tables = mysqli_query($conn, $query);
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
<?php include("includes/_layoutAdmin.php"); ?>

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-8 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <form id="tableForm" method="post">
                            <div class="d-flex mb-4 align-items-center">
                                <div class="d-flex align-items-center mr-3 w-100">
                                    <label class="w-25">Số bàn tối đa:</label>
                                    <input class="form-control" type="text" placeholder="Số bàn tối đa" name="tableCount">
                                </div>
                                <button class="btn btn-info btn-icon-text" id="saveButton" type="button">
                                    <i class="ti-file btn-icon-prepend"></i>Lưu
                                </button>
                            </div>
                        </form>

                        <!-- Table display -->
                        <div class="row">
                            <?php while ($item = mysqli_fetch_assoc($tables)): ?>
                                <?php
                                $slot = ($item['Status'] == true) ? "Có người" : "Trống";
                                $color = ($item['Status'] == true) ? "bg-success" : "bg-info";
                                ?>
                                <div class="col-3 mb-3">
                                    <a class="btn square-card card <?= $color ?> text-white"
                                       href="order.php?tableName=<?= urlencode($item['TableName']) ?>">
                                        <div class="card-body square-card-content">
                                            <p style="font-size: 30px"><?= $item['TableName'] ?></p>
                                            <p><?= $slot ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>