<?php 
require_once("includes/session_user.php");
$tableName = isset($_GET['tableName']) ? htmlspecialchars($_GET['tableName']) : ""; 
$tableID = null;

if ($tableName !== "") {
    $stmt = $conn->prepare("SELECT TableID FROM tables WHERE TableName = ?");
    $stmt->bind_param("s", $tableName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tableID = $row['TableID']; // Lưu tableID
    } else {
        echo "No tableID found for tableName: $tableName";
        exit;
    }
}

// Lưu trữ món đã chọn trong session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedDrinks'])) {
    $_SESSION['selectedDrinks'] = $_POST['selectedDrinks'];
}

// Fetching data function
function fetchData($conn, $sql) {
    $data = [];
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fetch required data
$listDrinkCategories = fetchData($conn, "SELECT * FROM drinkcategories");
$listDrinks = fetchData($conn, "SELECT * FROM drinks");
$billInfos = fetchData($conn, "SELECT * FROM billinfos");
// Fetch customer names from the database
$customers = fetchData($conn, "SELECT CustomerID, CustomerName FROM customer");


// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm hóa đơn</title>
    <link rel="stylesheet" href="path-to-your-css-file.css"> 
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script src="path-to-your-js-file.js"></script> 
    <style>
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Space between columns */
        }
        .form-column {
            flex: 1; /* Equal width columns */
        }
        .text-center {
            text-align: center;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 75%;
            margin-top: 20px;
        }
        .form-section {
            width: 110%;
            padding: 10px;
            margin: 60px;
            background-color: #f8f9fa;
            border-radius: 8px;
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
        .square-card {
            width: 100%;
            padding-top: 100%;
            position: relative;
            overflow: hidden;
        }
        .card-img-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            transition: height 0.5s ease-in-out;
        }
        .square-card:hover .card-body {
            height: 100%;
        }
        .row .category-link {
            text-decoration: none;
            color: black;
        }
        .row .selected-drink {
            color: dodgerblue;
        }
        .row .col-2 a {
            border-bottom: 1px solid black;
        }
        .row .col-2 a:last-child {
            border-bottom: none;
        }
        #customerName {
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
            font-size: 16px;
            width: 300px;
            margin-left: 20px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        #customerName:focus {
            border-color: #0056b3; /* Màu sắc khi ô nhập đang được focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Tạo bóng mờ khi focus */
        }
        #customer-status {
            font-weight: bold;
            margin-left: 10px;
            font-size: 16px;
        }
    </style>
    <script>
        let selectedDrinks = {};

        function addToDrink(drinkID) {
            if (!selectedDrinks[drinkID]) {
                selectedDrinks[drinkID] = { quantity: 1 };
            } else {
                selectedDrinks[drinkID].quantity++;
            }
            updateDrinkInfo();
        }

        function decreaseDrink(drinkID) {
            if (selectedDrinks[drinkID]) {
                selectedDrinks[drinkID].quantity--;

                // Nếu số lượng <= 0 thì xóa món khỏi danh sách
                if (selectedDrinks[drinkID].quantity <= 0) {
                    delete selectedDrinks[drinkID];
                }
                updateDrinkInfo();
            }
        }

        function updateDrinkInfo() {
            let tableBody = document.querySelector("#bill-table tbody");
            tableBody.innerHTML = ""; // Xóa nội dung cũ
            let total = 0;

            for (let id in selectedDrinks) {
                let quantity = selectedDrinks[id].quantity;
                let drinkElement = document.querySelector(`[data-drink-id="${id}"]`);
                let drinkName = drinkElement.getAttribute("data-drink-name");
                let drinkPrice = parseInt(drinkElement.getAttribute("data-drink-price"));
                let price = quantity * drinkPrice;
                total += price;

                let row = `
                    <tr>
                        <td>${drinkName}</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="decreaseDrink(${id})">-</button>
                            ${quantity}
                            <button class="btn btn-danger btn-sm" onclick="addToDrink(${id})">+</button>
                        </td>
                        <td>${price}₫</td>
                    </tr>`;
                tableBody.innerHTML += row;
            }

            // Cập nhật tổng tiền
            document.getElementById("total-amount").textContent = total + "₫";

            // Hiện/ẩn phần thanh toán
            document.getElementById("payment-section").style.display = total > 0 ? "block" : "none";
        }

        function processPayment() {
            const customerID = document.getElementById("customerName").value.trim();
            if (!customerID) {
                Swal.fire('Vui lòng chọn khách hàng', '', 'warning');
                return;
            }

            const totalAmount = document.getElementById("total-amount").textContent.replace("₫", "").trim();
            const items = Object.keys(selectedDrinks).map(id => ({
                drinkID: id,
                quantity: selectedDrinks[id].quantity,
            }));

            // Send payment request with customerID included
            fetch('payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    tableID: <?= json_encode($tableID) ?>,
                    items,
                    totalAmount,
                    customerID  // Send customerID
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Thanh toán thành công!', '', 'success').then(() => {
                        window.location.href = 'dashboard.php';
                    });
                } else {
                    Swal.fire('Có lỗi xảy ra!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Có lỗi xảy ra!', 'Vui lòng thử lại sau.', 'error');
            });
        }

        function filterCategory(event, categoryID) {
            event.preventDefault(); // Ngăn link reload trang

            // Ẩn tất cả các món
            document.querySelectorAll('.drink-item').forEach(item => {
                item.style.display = 'none';
            });

            // Hiển thị các món thuộc loại được chọn
            document.querySelectorAll(`.drink-item[data-category-id="${categoryID}"]`).forEach(item => {
                item.style.display = 'block';
            });

            // Làm nổi bật loại được chọn
            document.querySelectorAll('.category-link').forEach(link => {
                link.style.fontWeight = 'normal';
                link.style.color = 'black';
            });
            event.target.style.fontWeight = 'bold';
            event.target.style.color = 'dodgerblue';
        }

        // Hiển thị mặc định loại đầu tiên khi load trang
        document.addEventListener('DOMContentLoaded', () => {
            const firstCategory = document.querySelector('.category-link');
            if (firstCategory) {
                firstCategory.click();
            }
        });
        //
        function resetTableStatus() {
            // Gửi yêu cầu POST để cập nhật trạng thái bàn
            fetch('reset_table_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tableID: <?= json_encode($tableID) ?> })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Có lỗi xảy ra! Mã trạng thái: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Trạng thái đã được cập nhật thành Trống!', '', 'success').then(() => {
                        window.location.href = 'dashboard.php';
                    });
                } else {
                    Swal.fire('Có lỗi xảy ra!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Có lỗi xảy ra!', 'Vui lòng thử lại sau.', 'error');
            });

        }
    </script>
</head>

<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container mt-4">
        <form enctype="multipart/form-data" class="form-section full-page-wrapper">
            <div class="card p-3 h-100">
                <div class="row h-100">
                    <div class="col-7">
                        <!-- Thêm ô nhập tên khách hàng -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2><?= htmlspecialchars($tableName) ?></h2>
                                <!--Tên khách hàng -->
                                <div class="form-group">
                                    <select id="customerName" class="form-control" required>
                                        <option value="">Chọn khách hàng</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer['CustomerID'] ?>"><?= htmlspecialchars($customer['CustomerName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                            <div>
                                <!-- Nút Quay lại -->
                                <a href="dashboard.php" class="btn btn-primary" style="height: fit-content">
                                    <i class="ti-arrow-left"></i>
                                </a>

                                <!-- Nút Đổi lại trạng thái thành Trống -->
                                <button type="button" class="btn btn-danger ml-2" onclick="resetTableStatus()">X</button>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-2">
                                <?php foreach ($listDrinkCategories as $category): ?>
                                    <a href="#" data-category-id="<?= $category['DrinkCategoryID'] ?>" 
                                    class="py-3 d-block text-center category-link" 
                                    onclick="filterCategory(event, <?= $category['DrinkCategoryID'] ?>)">
                                        <?= htmlspecialchars($category['DrinkCategoryName']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-10 row drink-list" style="border-radius: 20px">
                                <?php foreach ($listDrinks as $item): ?>
                                    <div class="col-3 drink-item" 
                                        data-category-id="<?= $item['DrinkCategoryID'] ?>" 
                                        style="display: none;"> <!-- Ẩn mặc định -->
                                        <div class="btn square-card card drink-select" 
                                            data-drink-id="<?= $item['DrinkID'] ?>" 
                                            data-drink-name="<?= htmlspecialchars($item['DrinkName']) ?>" 
                                            data-drink-price="<?= $item['DrinkPrice'] ?>"
                                            onclick="addToDrink(<?= $item['DrinkID'] ?>)">
                                            <img src="images/drinks/<?= htmlspecialchars($item['DrinkImage']) ?>" class="card-img-top" />
                                            <div class="card-body"><?= htmlspecialchars($item['DrinkName']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            </div>
                        </div>
                    
                    <div class="col-5">
                        <div id="selected-drink-info" class="h-75" style="overflow-y: auto;">
                            <table class="table table-striped table-hover" id="bill-table">
                                <tbody>Hóa đơn <?= htmlspecialchars($tableName) ?></tbody>
                            </table>
                        </div>
                        <div id="payment-section" style="display: none;">
                            <hr />
                            <p class="text-right" style="font-size: 24px;">
                                Tổng tiền: <span id="total-amount">0₫</span>
                            </p>
                            <button type="button" class="btn btn-success btn-lg w-100" onclick="processPayment()">
                                Thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>