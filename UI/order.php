<?php 
require_once("includes/session_user.php");

// Handle table name from query string
$tableName = isset($_GET['tableName']) ? htmlspecialchars($_GET['tableName']) : ""; 
$tableID = null;

if ($tableName !== "") {
    $stmt = $conn->prepare("SELECT TableID FROM tables WHERE TableName = ?");
    $stmt->bind_param("s", $tableName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tableID = $row['TableID']; // Save tableID
    } else {
        echo "No tableID found for tableName: $tableName";
        exit;
    }
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
    <script>
        let selectedDrinks = {};

        function addToDrink(drinkID) {
            if (!selectedDrinks[drinkID]) {
                selectedDrinks[drinkID] = { quantity: 1 };
            } else {
                selectedDrinks[drinkID].quantity += 1;
            }
            updateDrinkInfo();
        }

        function updateDrinkInfo() {
            let tableBody = document.querySelector("#bill-table tbody");
            tableBody.innerHTML = "";

            let total = 0;
            for (let id in selectedDrinks) {
                let quantity = selectedDrinks[id].quantity;
                let drinkPrice = document.querySelector(`[data-drink-id='${id}']`).getAttribute("data-drink-price");
                let drinkName = document.querySelector(`[data-drink-id='${id}']`).getAttribute("data-drink-name");

                let price = quantity * drinkPrice;
                total += price;

                let row = document.createElement("tr");
                row.innerHTML = `<td>${drinkName}</td><td>${quantity}</td><td>${price}₫</td>`;
                tableBody.appendChild(row);
            }

            document.getElementById("total-amount").textContent = total + "₫";
            document.getElementById("payment-section").style.display = "block";
        }

        function processPayment() {
            const items = Object.keys(selectedDrinks).map(id => ({
                drinkID: id,
                quantity: selectedDrinks[id].quantity
            }));

            const totalAmount = document.getElementById('total-amount').textContent.replace(/\./g, "").replace("₫", "").trim();

            fetch('payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    tableID: <?= json_encode($tableID ?? null) ?>,
                    userID: <?= json_encode($userID ?? null) ?>,
                    items: items,
                    totalAmount: totalAmount
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

        document.addEventListener("DOMContentLoaded", function () {
            let drinkItems = document.querySelectorAll('.drink-item');
            let categoryLinks = document.querySelectorAll('.category-link');

            categoryLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    let categoryId = this.getAttribute('data-category-id');
                    drinkItems.forEach(item => {
                        item.style.display = (item.getAttribute('data-category-id') === categoryId) ? 'block' : 'none';
                    });
                    categoryLinks.forEach(link => link.classList.remove('selected-drink'));
                    this.classList.add('selected-drink');
                });
            });

            // Click the first category link automatically to show drinks if present
            if (categoryLinks.length > 0) categoryLinks[0].click();
        });

    </script>
    <style>
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
    </style>
</head>

<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container mt-4">
        <form enctype="multipart/form-data" class="form-section full-page-wrapper">
            <div class="card p-3 h-100">
                <div class="row h-100">
                    <div class="col-7">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2><?= htmlspecialchars($tableName) ?></h2>
                            <a href="dashboard.php" class="btn btn-primary" style="height: fit-content">
                                <i class="ti-arrow-left"></i>
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <?php foreach ($listDrinkCategories as $category): ?>
                                    <a href="#" data-category-id="<?= $category['DrinkCategoryID'] ?>" class="py-3 d-block text-center category-link">
                                        <?= htmlspecialchars($category['DrinkCategoryName']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-10 row" style="border-radius: 20px">
                                <?php foreach ($listDrinks as $item): ?>
                                    <div class="col-3 drink-item" data-category-id="<?= $item['DrinkCategoryID'] ?>">
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
                                <tbody>Hóa đơn bàn </tbody>
                            </table>
                        </div>
                        <div id="payment-section" style="display: none;">
                            <hr />
                            <p class="text-right" style="font-size: 24px;">Tổng tiền: <span id="total-amount">0₫</span></p>
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
