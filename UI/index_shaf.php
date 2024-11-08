<?php
include("includes/session_user.php");

// Check if the necessary variables are set, otherwise initialize them
$billCount = isset($billCount) ? $billCount : 0;
$uncollectedAmount = isset($uncollectedAmount) ? $uncollectedAmount : 0;
$revenue = isset($revenue) ? $revenue : 0;

// Check if the 'firstAmount' exists in the session, otherwise set a default value
$firstAmount = isset($_SESSION['firstAmount']) ? $_SESSION['firstAmount'] : 0;

$lastAmount = isset($lastAmount) ? $lastAmount : 0;
$user = isset($user) ? $user : ['FullName' => 'N/A'];
// Get the current date and time
$currentDateTime = date("Y-m-d H:i:s");  // Full date and time format (YYYY-MM-DD HH:MM:SS)
$firstTime = $currentDateTime;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Báo cáo kết ca</title>
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
</head>
<body>
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-8 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <h5 class="card-title">Báo cáo kết ca</h5>

                            <?php if (isset($error)): ?>
                                <p class="mt-2" style="color: red"><?= $error ?></p>
                            <?php endif; ?>

                            <form method="post" class="form-row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Số bill</label>
                                        <div class="form-control"><?= $billCount ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Đang phục vụ</label>
                                        <div class="form-control"><?= number_format($uncollectedAmount, 0, ',', '.') ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Doanh thu</label>
                                        <div class="form-control"><?= number_format($revenue, 0, ',', '.') ?> ₫</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Số tiền đầu ca</label>
                                        <div class="form-control"><?= number_format($firstAmount, 0, ',', '.') ?> ₫</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Số tiền kết ca</label>
                                        <div class="col-md-10">
                                            <input class="form-control" name="lastAmount" type="number" value="<?= $lastAmount ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Nhân viên</label>
                                        <div class="form-control"><?= $user['FullName'] ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Thời gian vào ca</label>
                                        <div class="form-control"><?= date("F j, Y g:i A", strtotime($firstTime)) ?></div> <!-- Display only the time -->
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-icon-text">
                                            <i class="ti-file btn-icon-prepend"></i>Kết ca
                                        </button>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>
</body>
</html>
