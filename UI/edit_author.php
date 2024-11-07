<?php
include("includes/session_user.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the UserCategoryID from the hidden input
    $userCategoryID = $_POST['UserCategoryID'];
    $functionIDs = isset($_POST['functionIDs']) ? $_POST['functionIDs'] : [];

    // Clear existing authorizations for this user category
    $deleteQuery = "DELETE FROM Authorizations WHERE UserCategoryID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userCategoryID);
    $stmt->execute();
    $stmt->close();

    // Insert new authorizations
    $insertQuery = "INSERT INTO Authorizations (UserCategoryID, FunctionID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    foreach ($functionIDs as $functionID) {
        $stmt->bind_param("ii", $userCategoryID, $functionID);
        $stmt->execute();
    }
    $stmt->close();

    // Redirect back to the index page
    header("Location: index_authorizations.php");
    exit();
}

// Fetch the user category model from the database using UserCategoryID (passed as GET parameter)
$userCategoryID = $_GET['id']; // Get the ID from the query string
$query = "SELECT * FROM UserCategories WHERE UserCategoryID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userCategoryID);
$stmt->execute();
$userCategory = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch functions and authorizations
$functionsQuery = "SELECT * FROM Funcitions";
$functionsResult = $conn->query($functionsQuery);

// Fetch authorizations for the current user category
$authorizationsQuery = "SELECT FunctionID FROM Authorizations WHERE UserCategoryID = ?";
$stmt = $conn->prepare($authorizationsQuery);
$stmt->bind_param("i", $userCategoryID);
$stmt->execute();
$authorizations = $stmt->get_result();
$authorizedFunctionIDs = [];
while ($row = $authorizations->fetch_assoc()) {
    $authorizedFunctionIDs[] = $row['FunctionID'];
}
$stmt->close();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phân quyền từng loại người dùng</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Include your Bootstrap CSS -->
    <style>
        .list-group-item {
            border: none;
            padding: 10px 15px;
            background-color: #f8f9fa;
        }

        .list-group-item:nth-child(odd) {
            background-color: #e9ecef;
        }

        .form-check-label {
            margin-left: 10px;
        }
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .form-section {
            width: 100%;
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
    <?php include("includes/_layoutAdmin.php");?>
    <div class="container mb-4">
    <form method="" class="form-section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="card-title">Phân quyền từng loại người dùng</h5>
                <a href="index_authorizations.php" class="btn btn-primary">
                    <i class="ti-arrow-left"></i>
                </a>
            </div>  
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <strong>Tên loại người dùng:</strong> <?php echo htmlspecialchars($userCategory['UserCategoryName']); ?>
                </li>
                <li class="list-group-item">
                    <strong>Mô tả:</strong> <?php echo htmlspecialchars($userCategory['UserCategoryDescription']); ?>
                </li>
            </ul>
            <hr />
            <form action="update_permissions.php" method="post">
                <input type="hidden" name="UserCategoryID" value="<?php echo htmlspecialchars($userCategory['UserCategoryID']); ?>">
                <div class="row">
                    <?php
                    // Group functions if necessary, you can implement your own grouping logic
                    $functionsGrouped = []; // Array to hold grouped functions
                    while ($function = $functionsResult->fetch_assoc()) {
                        $functionGroup = $function['FunctionGroup'];
                        $functionsGrouped[$functionGroup][] = $function;
                    }

                    foreach ($functionsGrouped as $groupKey => $groupFunctions) {
                        ?>
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6><?php echo htmlspecialchars($groupKey); ?></h6>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-secondary select-all">Chọn tất cả</button>
                                    <button type="button" class="btn btn-danger deselect-all">Hủy tất cả</button>
                                </div>
                            </div>
                            <ul class="list-group">
                                <?php foreach ($groupFunctions as $function): ?>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="functionIDs[]" value="<?php echo htmlspecialchars($function['FunctionID']); ?>" <?php echo in_array($function['FunctionID'], $authorizedFunctionIDs) ? "checked" : ""; ?>>
                                                <?php echo htmlspecialchars($function['FunctionName']); ?>
                                            </label>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Cập nhật quyền hạn</button>
            </form>
        </div>
    </div>
    </form>
    </div>

    <script src="path/to/jquery.js"></script> <!-- Include jQuery -->
    <script>
        // Xử lý sự kiện khi nhấn nút "Chọn tất cả"
        $('.select-all').click(function () {
            $(this).closest('.col-6').find('input[type="checkbox"]').prop('checked', true);
        });

        // Xử lý sự kiện khi nhấn nút "Hủy tất cả"
        $('.deselect-all').click(function () {
            $(this).closest('.col-6').find('input[type="checkbox"]').prop('checked', false);
        });
    </script>
</body>
</html>
