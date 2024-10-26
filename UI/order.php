<?php
include("includes/connectSQL.php");

$tableName = isset($_GET['tableName']) ? $_GET['tableName'] : ""; 
$tableName = htmlspecialchars($tableName); 
$check = true; 

// Lấy danh sách các danh mục đồ uống
$sqlCategories = "SELECT * FROM drinkcategories";
$resultCategories = $conn->query($sqlCategories);
$listDrinkCategories = [];
if ($resultCategories->num_rows > 0) {
    while($row = $resultCategories->fetch_assoc()) {
        $listDrinkCategories[] = $row;
    }
}

// Lấy danh sách đồ uống
$sqlDrinks = "SELECT * FROM drinks"; 
$resultDrinks = $conn->query($sqlDrinks);
$listDrinks = [];
if ($resultDrinks->num_rows > 0) {
    while($row = $resultDrinks->fetch_assoc()) {
        $listDrinks[] = $row;
    }
}

// Lấy thông tin hóa đơn
$sqlBillInfos = "SELECT * FROM billinfos"; 
$resultBillInfos = $conn->query($sqlBillInfos);
$billInfos = [];
if ($resultBillInfos->num_rows > 0) {
    while($row = $resultBillInfos->fetch_assoc()) {
        $billInfos[] = $row;
    }
}
// Đóng kết nối
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
        body {
            overflow-x: hidden;
        }
        .container {
            max-width: auto;
            margin-top: 20px;
        }
        .form-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            width: 120%;
            margin: 120px 0 80px;
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
        <form enctype="multipart/form-data" class="form-section">
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
                                        <div class="btn square-card card drink-select" data-drink-id="<?= $item['DrinkID'] ?>" data-drink-name="<?= htmlspecialchars($item['DrinkName']) ?>" data-drink-price="<?= $item['DrinkPrice'] ?>">
                                            <img src="images/drinks/<?= htmlspecialchars($item['DrinkImage']) ?>" class="card-img-top" />
                                            <div class="card-body"><?= htmlspecialchars($item['DrinkName']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div id="selected-drink-info" class="h-75" style="overflow-y: auto; display: none;">
                            <table class="table table-striped table-hover" id="bill-table">
                                <tbody>
                                    <!-- Thông tin đồ uống sẽ được thêm vào đây -->
                                </tbody>
                            </table>
                        </div>

                        <div class="h-25" id="payment-section" style="display: none;">
                            <hr />
                            <p class="text-right" style="font-size: 20px">Tổng tiền: <span id="total-amount">0</span>₫</p>
                            <hr />
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-info mr-2" id="btnExportPDF" href="#">Hóa đơn tạm tính</a>
                                <a class="btn btn-primary" id="btnThanhToan" href="#">Thanh toán</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var drinkItems = document.querySelectorAll('.drink-item');
            drinkItems.forEach(function (item) {
                item.style.display = 'none';
            });

            var categoryLinks = document.querySelectorAll('.category-link');
            categoryLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    var categoryId = this.getAttribute('data-category-id');
                    drinkItems.forEach(function (item) {
                        item.style.display = (item.getAttribute('data-category-id') === categoryId) ? 'block' : 'none';
                    });
                    categoryLinks.forEach(function (link) {
                        link.classList.remove('selected-drink');
                    });
                    this.classList.add('selected-drink');
                });
            });
            if (categoryLinks.length > 0) {
                categoryLinks[0].click();
            }

            var totalAmount = 0;
            var selectedDrinks = {};

            document.querySelectorAll('.drink-select').forEach(function (drink) {
                drink.addEventListener('click', function () {
                    var drinkID = this.getAttribute('data-drink-id');
                    var drinkName = this.getAttribute('data-drink-name');
                    var drinkPrice = parseFloat(this.getAttribute('data-drink-price'));

                    if (selectedDrinks[drinkID]) {
                        selectedDrinks[drinkID].quantity++;
                    } else {
                        selectedDrinks[drinkID] = {
                            name: drinkName,
                            price: drinkPrice,
                            quantity: 1
                        };
                    }

                    updateBillTable();
                });
            });

            function updateBillTable() {
                var tableBody = document.querySelector('#bill-table tbody');
                tableBody.innerHTML = '';
                totalAmount = 0;

                for (var id in selectedDrinks) {
                    if (selectedDrinks.hasOwnProperty(id)) {
                        var drink = selectedDrinks[id];
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${drink.name}</td>
                            <td>
                                <a href="#" class="bg-info p-2 mr-2 decrease-quantity" data-drink-id="${id}" style="color: white; border-radius: 20%">-
                                    
                                </a>
                                ${drink.quantity}
                                <a href="#" class="bg-info p-2 ml-2 increase-quantity" data-drink-id="${id}" style="color: white; border-radius: 20%">+
                                    
                                </a>
                            </td>
                            <td>${numberWithCommas(drink.price)}₫</td>
                            <td>                                              
                                <a class="btn btn-danger delete-item" href="#" data-drink-id="${id}">
                                    <i class="fa fa-trash-o fa-lg"></i> Delete
                                </a>                            
                            </td>
                        `;
                        tableBody.appendChild(row);
                        totalAmount += drink.price * drink.quantity;
                    }
                }

                document.getElementById('total-amount').textContent = numberWithCommas(totalAmount);

                // Hiển thị phần thông tin hóa đơn nếu có món uống được chọn
                if (Object.keys(selectedDrinks).length > 0) {
                    document.getElementById('selected-drink-info').style.display = 'block';
                    document.getElementById('payment-section').style.display = 'block';
                } else {
                    document.getElementById('selected-drink-info').style.display = 'none';
                    document.getElementById('payment-section').style.display = 'none';
                }
            }

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('increase-quantity')) {
                    e.preventDefault();
                    var id = e.target.getAttribute('data-drink-id');
                    selectedDrinks[id].quantity++;
                    updateBillTable();
                }

                if (e.target.classList.contains('decrease-quantity')) {
                    e.preventDefault();
                    var id = e.target.getAttribute('data-drink-id');
                    if (selectedDrinks[id].quantity > 1) {
                        selectedDrinks[id].quantity--;
                    } else {
                        delete selectedDrinks[id];
                    }
                    updateBillTable();
                }

                if (e.target.classList.contains('delete-item')) {
                    e.preventDefault();
                    var id = e.target.getAttribute('data-drink-id');
                    delete selectedDrinks[id];
                    updateBillTable();
                }
            });

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>
</body>
</html>