<?php
session_start();
require '../Common/db.php';

$username = $_SESSION['username'];
$newPhone = $_POST['phone'] ?? '';

try {
    $stmt = $pdo->prepare("UPDATE user SET phone_number = ? WHERE username = ?");
    $stmt->execute([$newPhone, $username]);

    echo "success: Profile updated successfully";
} catch (Exception $e) {
    echo "error: Update failed - " . $e->getMessage();
}
?>