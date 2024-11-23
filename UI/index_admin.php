<?php
include('includes/session_user.php');
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;


function getRevenueData() {
    global $conn;

    // Truy vấn doanh thu theo ngày
    $sql = "SELECT DATE(CreateDate) as RevenueDate, SUM(TotalAmount) as TotalRevenue
            FROM Bills
            GROUP BY DATE(CreateDate)
            ORDER BY RevenueDate";
    $result = $conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'date' => $row['RevenueDate'],
                'revenue' => (float) $row['TotalRevenue']
            ];
        }
    }

    return $data;
}


function getTableCount() {
    global $conn;

    $sql = "SELECT COUNT(*) as TableCount FROM Tables";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['TableCount'];
    }

    return 0; // Trả về 0 nếu không có bàn hoặc xảy ra lỗi
}

function getTotalEmployees() {
    global $conn;

    // Truy vấn đếm số lượng nhân viên có UsercategoryID khác 1
    $sql = "SELECT COUNT(*) AS TotalEmployees FROM Users WHERE UsercategoryID != 1";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['TotalEmployees'];
    }

    return 0; // Trả về 0 nếu không tìm thấy
}

function getDrinkCount() {
    global $conn;

    // Truy vấn để đếm tổng số lượng sản phẩm trong bảng Drinks
    $sql = "SELECT COUNT(*) as DrinkCount FROM Drinks";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['DrinkCount'];  // Trả về số lượng sản phẩm
    }

    return 0; // Trả về 0 nếu không có sản phẩm hoặc xảy ra lỗi
}

function getOrderCountToday() {
    global $conn;

    // Truy vấn số lượng khách hàng đặt hàng hôm nay
    $sql = "SELECT COUNT(DISTINCT BillID) as OrderCount FROM Bills WHERE DATE(CreateDate) = CURDATE()";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['OrderCount'];  // Trả về số lượng khách hàng
    }

    return 0; // Trả về 0 nếu không có đơn hàng hôm nay hoặc xảy ra lỗi
}

function getTotalCustomers() {
    global $conn;

    // Truy vấn đếm số lượng khách hàng trong bảng customer
    $sql = "SELECT COUNT(*) as TotalCustomers FROM customer";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['TotalCustomers'];  // Trả về số lượng khách hàng
    }

    return 0; // Trả về 0 nếu không có khách hàng hoặc xảy ra lỗi
}

function getRecentOrders() {
    global $conn;

    // Truy vấn lấy dữ liệu từ bảng Bills và Customer
    $sql = "SELECT b.CustomerID, c.CustomerName, b.TotalAmount, b.Status 
            FROM Bills b
            JOIN Customer c ON b.CustomerID = c.CustomerID
            ORDER BY b.CreateDate DESC
            LIMIT 10";  // Giới hạn 10 đơn hàng gần nhất

    $result = $conn->query($sql);

    $orders = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                'CustomerName' => $row['CustomerName'],
                'TotalAmount' => (float) $row['TotalAmount'],
                'Status' => $row['Status']
            ];
        }
    }

    return $orders;
}

// Lấy dữ liệu doanh thu
$revenueData = getRevenueData();
// Lấy số lượng bàn
$tableCount = getTableCount();
// Lấy số lượng nhân viên
$totalEmployees = getTotalEmployees();
// Lấy danh sách sản phẩm
$drinkData = getDrinkCount();
// Lấy số lượng khách hàng đã đặt hàng hôm nay
$orderCountToday = getOrderCountToday();
// Lấy số lượng khách hàng
$totalCustomers = getTotalCustomers();
// Lấy thông tin đơn hàng gần nhất
$recentOrders = getRecentOrders();


?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển quản trị CCMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 105%;
            margin: 0 auto;

        }

        .dashboard-container {
            padding: 30px;
        }

        .dashboard-card {
            background-color: #4e73df;
            color: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease-in-out;
            margin-top: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card i {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .dashboard-card h2 {
            font-size: 2.5rem;
            margin: 0;
        }

        .dashboard-card p {
            font-size: 1.1rem;
            margin: 5px 0;
        }

        .chart-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 30px;
        }

        .chart-box h3 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: #4e73df;
        }

        .recentOrders {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            margin-right: -150px;
            margin-left: 20px;
        }

        .cardHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cardHeader h2 {
            font-size: 1.8rem;
            margin: 0;
        }

        .cardHeader .btn {
            padding: 10px 20px;
            background-color: #4e73df;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cardHeader .btn:hover {
            background-color: #365ac5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
        }

        table th {
            background-color: #4e73df;
            color: #fff;
        }

        table td {
            background-color: #f8f9fc;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover td {
            background-color: #f1f3f7;
        }

        .status.delivered {
            background-color: #28a745;
        }

        .status.pending {
            background-color: #ffc107;
        }

        .status.inprogress {
            background-color: #17a2b8;
        }

        .status.return {
            background-color: #dc3545;
        }
        /* Trạng thái hoàn tất - màu xanh lá */
        .status.success {
            background-color: #28a745; /* Màu xanh lá */
            color: white;
            padding: 3px 10px;
            border-radius: 5px;
        }

        /* Trạng thái Thất bại - màu đỏ */
        .status.failed {
            background-color: #dc3545; /* Màu đỏ */
            color: white;
            padding: 3px 10px;
            border-radius: 5px;
        }

        /* Trạng thái mặc định (nếu có trạng thái khác) */
        .status {
            padding: 3px 10px;
            border-radius: 5px;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-card {
                margin-bottom: 20px;
                padding: 20px;
            }

            .chart-box {
                padding: 20px;
            }

            table th, table td {
                padding: 12px;
            }

            table {
                font-size: 0.95rem;
            }
        }
        .row-hi{
            display: flex;
    flex-wrap: wrap;
    margin-right: -150px;
    margin-left: 20px;
        }
    </style>
</head>

<body>
    <?php include_once('includes/_layoutAdmin.php'); ?>

    <div class="container dashboard-container">
        <div class="row-hi mb-4">
            <!-- Số bàn -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-card" onclick="window.location.href='dashboard.php';">
                    <i class="fas fa-table"></i>
                    <h2><?php echo $tableCount; ?></h2>
                    <p>Bàn</p>
                </div>
            </div>


            <!-- Sản phẩm -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-card" style="background-color: #1cc88a;" onclick="window.location.href='index_drink.php';">
                    <i class="fas fa-coffee"></i>
                    <h2><?php echo $drinkData; ?></h2>
                    <p>Sản phẩm</p>
                </div>
            </div>

            <!-- Nhân viên -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-card" style="background-color: #36b9cc;" onclick="window.location.href='index_users.php';">
                    <i class="fas fa-user-friends"></i>
                    <h2><?php echo $totalEmployees; ?></h2>
                    <p>Nhân viên</p>
                </div>
            </div>

            <!-- Khách hàng -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-card" style="background-color: #f6c23e;" onclick="window.location.href='index_customer.php';">
                    <i class="fas fa-users"></i>
                    <h2><?php echo $totalCustomers; ?></h2>
                    <p>Khách hàng</p>
                </div>
            </div>
        </div>

        <div class="row-hi">
            <!-- Biểu đồ doanh thu -->
            <div class="col-lg-6">
                <div class="chart-box">
                    <h3>Doanh thu theo ngày</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <!-- Biểu đồ khách hàng hôm nay -->
            <div class="col-lg-6">
                <div class="chart-box">
                    <h3>Khách hàng đặt hôm nay</h3>
                    <canvas id="orderTodayChart"></canvas>
                </div>
            </div>
        </div>
        <!----->
        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>Recent Orders</h2>
                    <a href="#" class="btn">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>Khách hàng</td>
                            <td>Tổng tiền</td>
                            <td>Trạng thái</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['CustomerName']) ?></td>
                                <td><?= number_format($order['TotalAmount'], 2) ?> VNĐ</td>
                                <td>
                                    <span class="status 
                                        <?= strtolower($order['Status']) === 'thành công' ? 'success' : (strtolower($order['Status']) === 'thất bại' ? 'failed' : '') ?>">
                                        <?= htmlspecialchars($order['Status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Dữ liệu doanh thu
        const revenueData = <?php echo json_encode($revenueData); ?>;
        const revenueLabels = revenueData.map(item => item.date);
        const revenueValues = revenueData.map(item => item.revenue);

        // Biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: revenueValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Dữ liệu khách đặt hôm nay
        const orderToday = <?php echo json_encode($orderCountToday); ?>;
        const orderCtx = document.getElementById('orderTodayChart').getContext('2d');
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đặt hàng hôm nay', 'Chưa đặt hàng'],
                datasets: [{
                    data: [orderToday, 100 - orderToday],
                    backgroundColor: ['#36b9cc', '#e0e0e0'],
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    </script>
</body>

</html>

