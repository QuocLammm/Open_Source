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
    $sql = "SELECT COUNT(DISTINCT UserID) as OrderCount FROM Bills WHERE DATE(CreateDate) = CURDATE()";
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

?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển quản trị CCMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Thư viện Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
        max-width: 900px;
        height: auto;
        margin-top: 10px;
        }
        .form-section {
            width: 105%;
            padding: 10px;
            margin: 50px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .dashboard-card {
            display: block;
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .dashboard-card .icon {
            font-size: 40px;
            margin-right: 15px;
            color: #fff;
        }

        .dashboard-card .data {
            font-size: 24px;
            text-align: center;
            color: #fff;
        }
        
        
    </style>
</head>

<body>
    <?php include_once('includes/_layoutAdmin.php'); ?>
    <div class="container mt-2">
        <form action="" method="POST" class="form-section">
            <div class="container dashboard">
                <h2 class="dashboard-title">Bảng điều khiển</h2>
                    <div class="row">
                        <!-----------------------------Số bàn----------------------------------->
                        <div class="col-md-3">
                            <a href="dashboard.php" class="dashboard-card" style="text-decoration: none;">
                                <div class="d-flex align-items-center">
                                    <!-- Biểu tượng -->
                                    <i class="fas fa-table icon"></i>
                                    <!-- Dữ liệu -->
                                    <div class="data">
                                        <h2><?php echo $tableCount; ?></h2>
                                        <p>Bàn</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-----------------------------Sản phẩm----------------------------------->
                        <div class="col-md-3">
                            <a href="index_drink.php" class="dashboard-card" style="text-decoration: none; background-color: #EE82EE">
                                <div class="d-flex align-items-center" >
                                    <!-- Icon -->
                                    <i class="fas fa-coffee icon" ></i>
                                    <div class="data">
                                        <h2><?php echo $drinkData; ?></h2>
                                        <p>Sản phẩm</p>
                                    </div>
                                </div>                                   
                            </a>
                        </div>
                        <!-----------------------------Nhân viên----------------------------------->
                        <div class="col-md-3">
                                <a href="index_users.php" class="dashboard-card" style="text-decoration: none; background-color: #00FFFF">
                                    <div class="d-flex align-items-center">
                                        <!-- Biểu tượng -->
                                        <i class="fas fa-user-friends icon"></i>
                                        <!-- Dữ liệu -->
                                        <div class="data">
                                            <h2><?php echo $totalEmployees; ?></h2>
                                            <p>Nhân viên</p>
                                        </div>
                                    </div>
                                </a>
                        </div>
                        <!-----------------------------Hihi----------------------------------->
                        <div class="col-md-3">
                                <a href="index_customer.php" class="dashboard-card" style="text-decoration: none; background-color: #00FF80">
                                    <div class="d-flex align-items-center">
                                        <!-- Biểu tượng -->
                                        <i class="fas fa-users icon"></i>
                                        <!-- Dữ liệu -->
                                        <div class="data">
                                            <h2><?php echo $totalCustomers; ?></h2>
                                            <p>Khách hàng</p>
                                        </div>
                                    </div>
                                </a>
                        </div>
                        <!-----------------------------Doanh thu theo ngày dựa trên bills----------------------------------->
                        <div class="col-md-6">
                            <h2>Doanh thu theo ngày</h2>
                            <canvas id="revenueChart" width="500" height="400"></canvas>
                        </div>
                        <!-----------------------------Tổng số khách hàng đặt hàng hôm nay----------------------------------->
                        <div class="col-md-6">
                            <h2>Khách hàng đặt hôm nay</h2>
                            <canvas id="orderTodayChart" width="500" height="400"></canvas>
                        </div>

                </div>
            </div>
        </form>
    </div>


    <script>
        // Nhận dữ liệu từ PHP
        const revenueData = <?php echo json_encode($revenueData); ?>;

        // Trích xuất labels và data từ revenueData
        const labels = revenueData.map(item => item.date);
        const revenues = revenueData.map(item => item.revenue);

        // Vẽ biểu đồ
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // Ngày
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: revenues, // Doanh thu
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

        // Nhận dữ liệu từ PHP cho số lượng khách hàng đã đặt hàng hôm nay
        const orderCountToday = <?php echo json_encode($orderCountToday); ?>;

        // Dữ liệu cho biểu đồ khách hàng đặt hàng hôm nay
        const orderTodayData = {
            labels: ['Hôm nay'], // Duy nhất một label "Hôm nay"
            datasets: [{
                label: 'Số khách hàng đã đặt hàng hôm nay',
                data: [orderCountToday], // Số lượng khách hàng
                backgroundColor: ['rgba(75, 192, 192, 0.6)'], // Màu nền cho biểu đồ hình tròn
                borderColor: ['rgba(75, 192, 192, 1)'], // Màu viền
                borderWidth: 1
            }]
        };

        // Vẽ biểu đồ cho khách hàng đã đặt hàng hôm nay (biểu đồ hình tròn)
        const ctxOrderToday = document.getElementById('orderTodayChart').getContext('2d');
        new Chart(ctxOrderToday, {
            type: 'pie',  // Thay đổi loại biểu đồ thành 'pie' (hình tròn)
            data: orderTodayData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',  // Đặt vị trí của legend ở trên
                    },
                    tooltip: {
                        enabled: true  // Bật tooltip khi hover
                    }
                }
            }
        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
