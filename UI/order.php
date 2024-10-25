<?php
include("includes/connectSQL.php");

// Khởi tạo biến $tableName và $check
// Lấy tên bàn từ URL
// Lấy tên bàn từ URL
$tableName = isset($_GET['tableName']) ? $_GET['tableName'] : ""; // Gán giá trị mặc định nếu không có

// Kiểm tra giá trị của $tableName
$tableName = htmlspecialchars($tableName);
$check = true; // Hoặc false, tùy thuộc vào logic của bạn

// Lấy danh sách các danh mục đồ uống
$sqlCategories = "SELECT * FROM drinkcategories";
$resultCategories = $conn->query($sqlCategories);
$listDrinkCategories = [];
if ($resultCategories->num_rows > 0) {
    while($row = $resultCategories->fetch_assoc()) {
        $listDrinkCategories[] = $row;
    }
}

// Lấy danh sách các đồ uống theo danh mục (giả sử có một tham số danh mục)
$categoryId = 1; // Thay đổi giá trị này theo yêu cầu của bạn
$sqlDrinks = "SELECT * FROM drinks WHERE drinkcategoryid = $categoryId"; // Thay đổi tên bảng nếu cần
$resultDrinks = $conn->query($sqlDrinks);
$listDrinks = [];
if ($resultDrinks->num_rows > 0) {
    while($row = $resultDrinks->fetch_assoc()) {
        $listDrinks[] = $row;
    }
}

// Lấy thông tin hóa đơn
$sqlBillInfos = "SELECT * FROM billinfos"; // Thay đổi tên bảng nếu cần
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
    <link rel="stylesheet" href="path-to-your-css-file.css"> <!-- Link CSS -->
    <script src="path-to-your-js-file.js"></script> <!-- Link JS -->
    <style>
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
                            <a href="#" data-category-id="<?= $category['DrinkCategoryID'] ?>"
                               class="py-3 d-block text-center category-link"><?= htmlspecialchars($category['DrinkCategoryName']) ?></a>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-10 row" style="border-radius: 20px">
                        <?php foreach ($listDrinks as $item): ?>
                            <div class="col-3 drink-item" data-category-id="<?= $item['DrinkCategoryID'] ?>">
                                <a class="btn square-card card" href="add_drink_to_bill.php?id=<?= $item['DrinkID'] ?>">
                                    <img src="/Public/images/drinks/<?= htmlspecialchars($item['DrinkImage']) ?>" class="card-img-top"/>
                                    <div class="card-body"><?= htmlspecialchars($item['DrinkName']) ?></div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <?php if ($check && !empty($billInfos)): ?>
                    <div class="h-75" style="overflow-y: auto; overflow-x: hidden">
                        <table class="table table-striped table-hover">
                            <tbody>
                            <?php foreach ($billInfos as $item): ?>
                                <tr data-id-drink="<?= $item['DrinkID'] ?>" data-id-bill="<?= $item['BillID'] ?>">
                                    <td><?= htmlspecialchars($item['Drinks']['DrinkName']) ?></td>
                                    <td>
                                        <a href="giam_so_luong.php?idDrink=<?= $item['DrinkID'] ?>&idBill=<?= $item['BillID'] ?>"
                                           class="bg-info p-1 mr-2" style="color: white; border-radius: 20%">
                                            <i class="mdi mdi-minus"></i>
                                        </a>
                                        <?= $item['DrinkCount'] ?>
                                        <a href="tang_so_luong.php?idDrink=<?= $item['DrinkID'] ?>&idBill=<?= $item['BillID'] ?>"
                                           class="bg-info p-1 ml-2" style="color: white; border-radius: 20%">
                                            <i class="mdi mdi-plus"></i>
                                        </a>
                                    </td>
                                    <td><?= number_format($item['Drinks']['DrinkPrice'], 0, ',', '.') ?>₫</td>
                                    <td>
                                        <a href="#" class="delete-item" style="text-decoration: none">
                                            <i class="mdi mdi-delete" style="font-size: 25px; color: red"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="h-25">
                        <hr/>
                        <p class="text-right" style="font-size: 20px">Tổng tiền:
                            <?= number_format($billInfos[0]['Bills']['TotalAmount'], 0, ',', '.') ?>₫
                        </p>
                        <hr/>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-info mr-2" id="btnExportPDF" href="#">
                                Hóa đơn tạm tính
                            </a>
                            <a class="btn btn-primary" id="btnThanhToan" href="#">
                                Thanh toán
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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

            $('#btnExportPDF').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'export_pdf.php',
                    type: 'GET',
                    success: function (rs) {
                        if (rs.success) {
                            location.reload();
                        }
                    }
                });
            });

            $('#btnThanhToan').on('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn thanh toán?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'thanh_toan.php',
                            type: 'GET',
                            success: function (rs) {
                                if (rs.success) {
                                    window.location.href = 'index.php';
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-item', function (e) {
                e.preventDefault();
                var row = $(this).closest('tr');
                var idDrink = row.data('id-drink');
                var idBill = row.data('id-bill');
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa đồ uống này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_drink.php',
                            type: 'POST',
                            data: {idDrink: idDrink, idBill: idBill},
                            success: function (rs) {
                                if (rs.success) {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });

            $('.js-example-basic-single').select2();
        });
    </script>
</body>
</html>
