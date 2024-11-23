<?php
include("includes/connectSQL.php");
$userID = isset($_COOKIE['UserID']) ? $_COOKIE['UserID'] : null;

if ($userID) {
    // Fetch user information
    $query = "SELECT FullName, UserImage, Email, PhoneNumber, Address, Gender FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 40px;
            width: 100%;
        }

        .profile-card {
            display: flex;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 85%;
            margin-top: 20px;
        }

        .profile-image {
            width: 35%;
            background-color: #f1f1f1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid #ddd;
            padding: 30px;
        }

        .profile-image img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #1cc88a;
        }

        .profile-image h3 {
            margin-top: 15px;
            font-size: 22px;
            color: #333;
            font-weight: 600;
        }

        .profile-info {
            width: 65%;
            padding: 30px;
        }

        .profile-info h2 {
            font-size: 26px;
            color: #333;
            margin-bottom: 25px;
        }

        .form-container {
            margin-bottom: 25px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 5px 0 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: #333;
            background-color: #fafafa;
            transition: border 0.3s ease, background-color 0.3s ease;
        }

        .form-container input:focus {
            border-color: #1cc88a;
            background-color: #fff;
            outline: none;
        }

        .form-container button {
            background-color: #1cc88a;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background-color: #17a673;
            transform: scale(1.05);
        }

        .form-container input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .form-container input[type="text"] {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <?php include("includes/_layoutAdmin.php"); ?>
    <div class="container">
        <div class="profile-card">
            <!-- Left: User Image and Name -->
            <div class="profile-image">
                <img src="images/users/<?= !empty($user['UserImage']) ? htmlspecialchars($user['UserImage']) : 'avatar.jpg' ?>" alt="Profile Picture">
                <h3><?= htmlspecialchars($user['FullName']) ?></h3>
            </div>

            <!-- Right: User Info Form -->
            <div class="profile-info">
                <h2>Thông tin cá nhân</h2>
                <form action="update_profile.php" method="POST">
                    <div class="form-container">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" readonly>
                    </div>
                    <div class="form-container">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['PhoneNumber']) ?>" readonly>
                    </div>
                    <div class="form-container">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['Address']) ?>" readonly>
                    </div>
                    <div class="form-container">
                        <label for="gender">Giới tính:</label>
                        <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($user['Gender']) ?>" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
