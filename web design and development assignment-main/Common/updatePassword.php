<?php
session_start();
require '../Common/db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['id'];

$currentPassword  = $_POST['current_password'] ?? '';
$newPassword      = $_POST['new_password'] ?? '';
$confirmPassword  = $_POST['confirm_password'] ?? '';

if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'New password and confirm password must match']);
    exit;
}

$stmt = $pdo->prepare("SELECT password FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$storedPassword = $user['password'];

if ($currentPassword !== $storedPassword) {
    echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
    exit;
}

$updateStmt = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
$updateStmt->execute([$newPassword, $user_id]);

echo json_encode(['success' => true]);
exit;
?>
