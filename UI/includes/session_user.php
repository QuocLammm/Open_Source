<?php
ob_start(); // Start output buffering
session_start();
include("includes/connectSQL.php");

// Function to fetch user details
function fetchUserDetails($conn, $userId) {
    $query = "
        SELECT users.FullName, users.UserImage, usercategories.UserCategoryName
        FROM users
        JOIN usercategories ON users.UsercategoryID = usercategories.UsercategoryID
        WHERE users.UserID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Fetch user information from cookies
$user = null;
$message = "";
if (isset($_COOKIE['UserID'])) {
    $userId = $_COOKIE['UserID'];
    $user = fetchUserDetails($conn, $userId);

    // Check user role based on UserCategoryName
    if ($user) {
        $userRole = $user['UserCategoryName'];

        // Determine which interface to display based on role
        if (strcasecmp($userRole, "Thu ngân") === 0) {
            $message = "Xin chào!";
        } elseif (strcasecmp($userRole, "Quản lý") === 0) {
            $message = "Xin chào!";
        } else {
            $message = "Chức vụ không xác định.";
        }
    } else {
        $message = "Không tìm thấy người dùng.";
    }
} else {
    $message = "User not logged in.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    #greeting {
        display: block;
        margin-bottom: 20px;
        font-size: 17px;
        text-align: center;
        max-width: 520px;
        margin: auto;
        color: white;
        animation: colorChange 6s ease-in-out infinite; /* Adjusted the timing */
    }

    @keyframes colorChange {
        0% {
            color: red; /* Starting color */
        }
        25% {
            color: orange;
        }
        50% {
            color: yellow;
        }
        75% {
            color: white;
        }
        100% {
            color: sienna; /* Ending color */
        }
    }
</style>
</head>
</html>