<?php
header('Content-Type: application/json');
session_start();
require '../common/db.php';

$password = $_POST['password'] ?? '';
$username = $_POST['username'] ?? '';

if (empty($password) || empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

if ($password !== $row['password']) {
    echo json_encode(['success' => false, 'message' => 'Password incorrect']);
    exit;
}

$_SESSION['id'] = $row['id'];
$_SESSION['username'] = $row['username'];
$_SEESION['name'] = $row['name'];
$_SESSION['role'] = $row['role'];
$_SESSION['user_type'] = $row['user_type'];  // staff/student (if role=user)

// Redirect based on role
switch ($row['role']) {
    case 'admin':
        echo json_encode(['success' => true, 'redirect' => '../Admin/userManagement.php']);
        exit;

    case 'food_donor':
        echo json_encode(['success' => true, 'redirect' => '../FoodDonor/dashboard.php']);
        exit;

    case 'user':
        // inside user, you can still separate by user_type if needed
        if ($_SESSION['user_type'] === 'staff') {
            echo json_encode(['success'=>true,'redirect'=>'../Users/Staff/staffDashboard.php']);
        } else {
            echo json_encode(['success'=>true,'redirect'=>'../Users/Student/studentDashboard.php']);
        }
        exit;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown role']);
        exit;
}
