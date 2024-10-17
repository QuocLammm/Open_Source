<?php
// Establish the connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlcoffee"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the SQL query to fetch user categories
$query = "SELECT UserCategoryID, UserCategoryName, UserCategoryDescription FROM UserCategories";

// Execute the query and store the result in $result
$result = $conn->query($query);

// Check if the query execution was successful
if ($result === false) {
    echo "Error: " . $conn->error; // Display error if query fails
} else {
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Danh Sách Loại Người Dùng</title>
    <style>
        .search-form {
            max-width: 400px; /* Set desired width */
        }
        .nav-link { color: #000; }
        .table td, .table th { vertical-align: middle; }
        .mdi-pencil, .mdi-delete { font-size: 1.2em; cursor: pointer; }
        .mdi-pencil { color: #4CAF50; }
        .mdi-delete { color: #F44336; }
        .custom-border {
            border: 2px solid #28a745; /* Adjust border color and thickness as needed */
        }
        input.form-control {
            border: 1px solid #ced4da; /* Add a border to the input */
        }
        input.text{
          width: 1000px;
        }

        button.btn {
            border: 1px solid #ced4da; /* Add a border to buttons */
            width: 100px;
        }


    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <!-- Include your layout for the sidebar or navigation here -->
                <?php include('E:\HK1_2025\OpenSource\bt_xampp\Open_Source\_layoutAdmin.php'); ?>
            </div>
            <div class="col-md-10">
                <h3 class="my-4">Danh Sách Loại Người Dùng</h3>
                <div class="text-end mt-2"> <!-- Added a separate div for alignment -->
                  <a class="btn btn-success border border-success text-white" href="create.php">+</a> <!-- Border class applied -->
                </div>
                <!-- Search Form -->
                <div class="mb-3 search-form">
                  <form action="" method="GET" class="d-flex align-items-center">
                      <div class="me-3">
                          <h6>Loại người dùng:</h6>
                      </div>
                      <div class="me-2">
                          <input type="text" name="search" class="form-control" placeholder="Tên loại người dùng" aria-label="Search">
                      </div>
                      <div class="me-2">
                          <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                      </div>
                      <div>
                          <button class="btn btn-secondary" type="reset">Làm mới</button>
                      </div>
                  </form>
                </div>

                <!-- Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>#</th>
                            <th>Tên loại người dùng</th>
                            <th>Mô tả</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' class='user-checkbox' data-id='{$row['UserCategoryID']}'></td>";
                                echo "<td>{$row['UserCategoryID']}</td>";
                                echo "<td>{$row['UserCategoryName']}</td>";
                                echo "<td>{$row['UserCategoryDescription']}</td>";
                                echo "<td>
                                        <i class='mdi mdi-pencil' onclick='editUser({$row['UserCategoryID']})'></i>
                                        <i class='mdi mdi-delete' onclick='deleteUser({$row['UserCategoryID']})'></i>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Không có bản ghi nào</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script> <!-- Thay đổi đường dẫn đến tệp JS nếu cần -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
}
?>
