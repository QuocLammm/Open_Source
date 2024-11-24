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
$listDrinks = fetchData($conn, "SELECT * FROM drinks LIMIT 7");
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
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>/* Bảng thanh toán */
#bill-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 20px;
}

#bill-table th, #bill-table td {
    padding: 12px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
}

#bill-table th {
    background-color: #f2f2f2;
    font-weight: bold;
    color: #333;
    font-size: 16px;
}

#bill-table tr:hover {
    background-color: #f1f1f1;
}


/* Thêm cuộn khi danh sách món vượt quá 4 */
#bill-table tbody {
    max-height: 250px; /* Điều chỉnh chiều cao tối đa tùy ý */
    overflow-y: auto; /* Kích hoạt cuộn dọc */
    display: block;
    width: 100%;
}

/* Giữ cố định header của bảng */
#bill-table thead {
    display: table;
    width: 100%;
}


/* Phần thanh toán */
.payment-section {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    position: sticky;
    top: 0;
    z-index: 10;
}

.payment-section button {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
}

.payment-section button:hover {
    background-color: #218838;
}

.payment-section select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    margin-top: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.payment-section h5 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
}

.payment-section label {
    font-size: 16px;
    font-weight: bold;
}

/* Form Input và Select */
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

/* Layout */
.category-filter {
    margin-left: 100px;
}

.form-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.form-column {
    flex: 1;
}

.text-center {
    text-align: center;
}

/* Nút */
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

/* Container */
.container {
    max-width: 75%;
    margin-top: 20px;
}

/* Form Section */
.form-section {
    width: 110%;
    padding: 10px;
    margin: 40px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

/* Xóa item */
.btnDelete {
    cursor: pointer;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.pagination a {
    text-decoration: none;
    padding: 8px 12px;
    border: 1px solid #007bff;
    border-radius: 5px;
    color: #007bff;
}

.pagination a:hover {
    background-color: #007bff;
    color: white;
}

.pagination strong {
    color: red;
    border: 1px solid #007bff;
    padding: 8px 12px;
    border-radius: 5px;
}

/* Hình ảnh món uống */
.drink-image {
    width: 50px;
    height: 50px;
    cursor: pointer;
    border-radius: 5px;
}

.drink-image:hover {
    opacity: 0.8;
}

/* Menu Grid */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 20px;
}

.menu-item {
    position: relative;
    width: 100%;
    height: 300px;
    border: 1px solid #ccc;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.menu-item:hover {
    transform: scale(1.05);
}

.menu-item-image {
    width: 100%;
    height: 80%;
    overflow: hidden;
}

.menu-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.menu-item-info {
    padding: 10px;
    text-align: center;
}

.menu-item-name {
    font-size: 16px;
    font-weight: bold;
    margin: 5px 0;
}

.menu-item-price {
    font-size: 14px;
    color: #f75c5c;
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
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="removeDrink(${id})">
                                <i class="fa fa-trash"></i> <!-- Thêm icon xóa -->
                            </button>
                        </td>
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

            // Gửi yêu cầu thanh toán với customerID đã bao gồm
            fetch('payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    tableID: <?= json_encode($tableID) ?>,
                    items,
                    totalAmount,
                    customerID  // Gửi customerID
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
            event.preventDefault(); // Prevent page reload when clicking a button

            // Hide all drinks
            document.querySelectorAll('.menu-item').forEach(item => {
                item.style.display = 'none';
            });

            // Show drinks of the selected category
            document.querySelectorAll(`.menu-item[data-category-id="${categoryID}"]`).forEach(item => {
                item.style.display = 'block';
            });

            // Highlight the selected category button
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
            });

            document.querySelector(`#category-${categoryID}`).classList.remove('btn-secondary');
            document.querySelector(`#category-${categoryID}`).classList.add('btn-primary');
        }
        function removeDrink(drinkID) {
            if (selectedDrinks[drinkID]) {
                delete selectedDrinks[drinkID]; // Xóa món khỏi danh sách
                updateDrinkInfo(); // Cập nhật lại bảng
            }
        }
    </script>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container">
        <form method="POST">
            <div class="form-section">
                <div class="text-center">
                    <br>
                    <h2>Chọn món cho <?= $tableName ?></h2>
                </div>
                <!-- Category Filter -->
                <div class="category-filter">
                    <div class="btn-group">
                        <?php foreach ($listDrinkCategories as $category): ?>
                            <button 
                                type="button" 
                                class="btn btn-secondary category-btn" 
                                id="category-<?= $category['DrinkCategoryID'] ?>"
                                onclick="filterCategory(event, <?= $category['DrinkCategoryID'] ?>)">
                                <?= $category['DrinkCategoryName'] ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <br>
                <div class="form-row">
                    <div class="form-column">
                        <div class="menu-grid">
                            <?php foreach ($listDrinks as $drink): ?>
                                <div class="menu-item" data-category-id="<?= $drink['DrinkCategoryID'] ?>" data-drink-id="<?= $drink['DrinkID'] ?>" data-drink-name="<?= $drink['DrinkName'] ?>" data-drink-price="<?= $drink['DrinkPrice'] ?>" data-drink-image="<?= $drink['DrinkImage'] ?>">
                                    <div class="menu-item-image">
                                        <img src="images/drinks/<?= $drink['DrinkImage'] ?>" alt="<?= $drink['DrinkName'] ?>" class="drink-image" onclick="addToDrink(<?= $drink['DrinkID'] ?>)">
                                    </div>
                                    <div class="menu-item-info">
                                        <p class="menu-item-name"><?= $drink['DrinkName'] ?></p>
                                        <p class="menu-item-price"><?= $drink['DrinkPrice'] ?>₫</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-column">
                        <h5>Danh sách món đã chọn</h5>
                        <table id="bill-table" class="table-bordered">
                            <tbody></tbody>
                        </table>
                        <div class="payment-section">
                            <h5>Tổng tiền: <span id="total-amount">0₫</span></h5>
                            <div class="form-group">
                                <label for="customerName">Khách hàng</label>
                                <select id="customerName" class="form-control" required>
                                    <option value="">Chọn khách hàng</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['CustomerID'] ?>"><?= $customer['CustomerName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="btn" onclick="processPayment()">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
