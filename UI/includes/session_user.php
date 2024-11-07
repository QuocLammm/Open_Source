<?php
ob_start(); // Start output buffering
session_start();
include("includes/connectSQL.php");
// Function to fetch user details
function fetchUserDetails($conn, $userId) {
    $query = "SELECT FullName, UserImage FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Fetch user information from cookies
$user = null;
if (isset($_COOKIE['UserID'])) {
    $userId = $_COOKIE['UserID'];
    $user = fetchUserDetails($conn, $userId);
} else {
    echo "User not logged in.";
    exit;
}
?>