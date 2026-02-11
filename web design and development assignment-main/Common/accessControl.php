<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
require_once __DIR__ . '/db.php';


if (!isset($_SESSION['username'])) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$currentUrl = $_SERVER['REQUEST_URI'];

if (strpos($currentUrl, "/Admin/") !== false && $user['role'] !== 'admin') {
    echo "You are not allowed to access Admin pages because your role is '{$user['role']}'.";
    header("refresh:5;url=../LoginSignUp/login.php");
    exit;
}

if (strpos($currentUrl, "/FoodDonor/") !== false && $user['role'] !== 'food_donor') {
    echo "You are not allowed to access Food Donor pages because your role is '{$user['role']}'.";
    header("refresh:5;url=../LoginSignUp/login.php");
    exit;
}

// Student protection (Users/Student)
if (strpos($currentUrl, "/Users/Student/") !== false) {
    if (($user['role'] ?? '') !== 'user' || ($user['user_type'] ?? '') !== 'student') {
        header("refresh:5;url=../LoginSignUp/login.php");
        exit;
    }
}

// Staff protection (Users/Staff)
if (strpos($currentUrl, "/Users/Staff/") !== false) {
    if (($user['role'] ?? '') !== 'user' || ($user['user_type'] ?? '') !== 'staff') {
        header("refresh:5;url=../RWDD_assignment/LoginSignUp/login.php");
        exit;
    }
}

?>