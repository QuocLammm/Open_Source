
<?php
include('includes/session_user.php');
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;
$usercategoriesID = null;

if ($userID) {
    // Fetch the user's category IDs
    $queryCategory = " SELECT users.FullName, users.UserImage, usercategories.UserCategoryName
                        FROM users
                        JOIN usercategories ON users.UsercategoryID = usercategories.UsercategoryID
                        WHERE users.UserID = ?";
    $stmt = $conn->prepare($queryCategory);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $resultCategory = $stmt->get_result();
    $userCategory = $resultCategory->fetch_assoc();
    $usercategoriesID = $userCategory['UserCategoryName'];
    // Array of roles to check against
    $validRoles = ["Quản Lý", "Thu Ngân"];
}


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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Redesign</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Thư viện Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>

  <body>
    <div class="container">
      <div class="navigation">
        <ul>
          <li>
            <a href="#">
              <span class="icon"><i class="fas fa-coffee icon" ></i></span>
              <span class="title" style="font-size: 1.5em; font-weight: 500"
                >L&T Coffee</span
              >
            </a>
          </li>
          <li class="hovered">
            <a href="text.php">
              <span class="icon"
                ><ion-icon name="home-outline"></ion-icon
              ></span>
              <span class="title">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="dashboard.php">
              <span class="icon"
                ><ion-icon name="people-outline"></ion-icon
              ></span>
              <span class="title">Bán hàng</span>
            </a>
          </li>
          <li>
            <a href="index_drink.php">
              <span class="icon"
                ><ion-icon name="chatbubble-outline"></ion-icon
              ></span>
              <span class="title">Đồ uống</span>
            </a>
          </li>
          <li>
            <a href="#">
              <span class="icon"
                ><ion-icon name="help-outline"></ion-icon
              ></span>
              <span class="title">Help</span>
            </a>
          </li>
          <li>
            <a href="#">
              <span class="icon"
                ><ion-icon name="settings-outline"></ion-icon
              ></span>
              <span class="title">Settings</span>
            </a>
          </li>
          <li>
            <a href="#">
              <span class="icon"
                ><ion-icon name="lock-closed-outline"></ion-icon
              ></span>
              <span class="title">Password</span>
            </a>
          </li>
          <li>
            <a href="login.php">
              <span class="icon"
                ><ion-icon name="log-out-outline"></ion-icon
              ></span>
              <span class="title">Sign Out</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- main -->
      <div class="main">
        <div class="topbar">
          <div class="toggle">
            <ion-icon name="menu-outline"></ion-icon>
          </div>
          <div class="search">
            <label>
              <input type="text" placeholder="Search here" />
              <ion-icon name="search-outline"></ion-icon>
            </label>
          </div>
          <div class="user">
            <img src="images/users/1.jpg" />
          </div>
        </div>
        <div class="cardBox">
        <!-- Số bàn--->
            <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php echo $tableCount;?>
                        </div>
                        <div class="cardName">Total Tables</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cafe-outline"></ion-icon>
                    </div>
                </div>
            </a>


          <!-- Sản phẩm--->
            <a href="index_drink.php" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php echo $drinkData;?>
                        </div>
                        <div class="cardName">Sản phẩm</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cafe-outline"></ion-icon>
                    </div>
                </div>
            </a>
            <!-- Nhân viên--->
            <a href="index_users.php" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php echo $totalEmployees;?>
                        </div>
                        <div class="cardName">Nhân viên</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>
            </a>
          
          <!-- Khách hàng--->
          <a href="index_users.php" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php echo $totalCustomers;?>
                        </div>
                        <div class="cardName">Khách hàng</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>
            </a>

        </div>
        <div class="graphBox">
          <div class="box">
            <canvas id="myChart"></canvas>
          </div>
          <div class="box">
            <canvas id="earning"></canvas>
          </div>
        </div>

        <div class="details">
          <div class="recentOrders">
            <div class="cardHeader">
              <h2>Recent Orders</h2>
              <a href="#" class="btn">View All</a>
            </div>
            <table>
              <thead>
                <tr>
                  <td>Name</td>
                  <td>Price</td>
                  <td>Payment</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Star Refrigerator</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status delivered">Delivered</span></td>
                </tr>
                <tr>
                  <td>Window Coolers</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>Speakers</td>
                  <td>$620</td>
                  <td>Paid</td>
                  <td><span class="status return">Return</span></td>
                </tr>
                <tr>
                  <td>Hp Laptop</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status inprogress">In Progress</span></td>
                </tr>
                <tr>
                  <td>Apple Watch</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status delivered">Delivered</span></td>
                </tr>
                <tr>
                  <td>Wall Fan</td>
                  <td>$110</td>
                  <td>Paid</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>Adidas Shoes</td>
                  <td>$620</td>
                  <td>Paid</td>
                  <td><span class="status return">Return</span></td>
                </tr>
                <tr>
                  <td>Denim Shirts</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status inprogress">In Progress</span></td>
                </tr>
                <tr>
                  <td>Casual Shoes</td>
                  <td>$575</td>
                  <td>Paid</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>Wall Fan</td>
                  <td>$110</td>
                  <td>Paid</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>Denim Shirts</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status inprogress">In Progress</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="recentCustomers">
            <div class="cardHeader">
              <h2>Recent Customers</h2>
            </div>
            <table>
              <tr>
                <td width="60px">
                  <div class="imgBx"><img src="img1.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>Italy</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img2.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>India</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img3.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>France</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img4.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>USA</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img5.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>Japan</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img6.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>India</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img7.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>Malaysia</span></h4>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="imgBx"><img src="img8.jpg" /></div>
                </td>
                <td>
                  <h4>Coding World<br /><span>India</span></h4>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script
      type="module"
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="js/my_chart.js"></script>
    <script>
      let toggle = document.querySelector(".toggle");
      let navigation = document.querySelector(".navigation");
      let main = document.querySelector(".main");

      toggle.onclick = function () {
        navigation.classList.toggle("active");
        main.classList.toggle("active");
      };
      let list = document.querySelectorAll(".navigation li");
      function activeLink() {
        list.forEach((item) => item.classList.remove("hovered"));
        this.classList.add("hovered");
      }
      list.forEach((item) => item.addEventListener("mouseover", activeLink));
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  </body>
</html>