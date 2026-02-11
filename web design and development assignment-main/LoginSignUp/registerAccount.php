<?php
header('Content-Type: application/json');
session_start();
require '../common/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$username = $_POST['username'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$role = $_POST['role'] ?? '';

if (empty($email) || empty($password) || empty($username) || empty($confirmPassword) || empty($role)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

if ($password != $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Password and confirm password must be same']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email already registered']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO user (email, password, username, role, status) VALUES (?, ?, ?, ?, 'active')");
$stmt->execute([$email, $password, $username, $role]);
echo json_encode(['success' => true]);

?>
