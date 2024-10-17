

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm loại người dùng</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css"> <!-- Thay đổi đường dẫn đến tệp CSS nếu cần -->
</head>
<body>
    <?php include('includes\_layoutAdmin.php');?>
<div class="col-md-10">
          <!-- Placeholder for page-specific content -->
        </div>
      </div>
</div>
<div class="container mt-5">
    <h3>Thêm loại người dùng</h3>
    <a href="index.php" class="btn btn-primary mb-2">
        <i class="ti-arrow-left"></i> Quay lại
    </a>
    
    <!--<!-?php if ($errorMessage): ?>
        <div class="alert alert-danger"><!?php echo $errorMessage; ?></div>
    <!?php endif; ?>
    
    <!?php if ($successMessage): ?>
        <div class="alert alert-success"><!?php echo $successMessage; ?></div>
    <!!?php endif; ?>-->

    <form method="post" action="">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="userCategoryName">Tên loại người dùng <span class="text-danger">*</span></label>
                    <input type="text" id="userCategoryName" name="userCategoryName" class="form-control" value="<?php echo htmlspecialchars($userCategoryName ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="userCategoryDescription">Mô tả</label>
                    <textarea id="userCategoryDescription" name="userCategoryDescription" class="form-control" rows="4"><?php echo htmlspecialchars($userCategoryDescription ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn btn-info">Lưu</button>
            </div>
        </div>
    </form>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script> <!-- Thay đổi đường dẫn đến tệp JS nếu cần -->
</body>
</html>
