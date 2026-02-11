<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Common/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}

$username = $_SESSION['username'];

$stmt = $pdo->prepare(
    "SELECT id, username, role, status 
     FROM user 
     WHERE username = ? 
     LIMIT 1"
);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

if ($user['role'] !== 'admin') {
    die("Access denied. Admin only.");
}

if (strtolower($user['status']) !== 'active') {
    die("Account is not active.");
}

$_SESSION['user_id'] = $user['id'];
