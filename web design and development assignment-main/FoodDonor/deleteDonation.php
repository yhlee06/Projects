<?php
session_start();
require '../Common/db.php';

$id = $_POST['id'] ?? null;

try {
    $stmt = $pdo->prepare("DELETE FROM food_donation WHERE id = ?");
    $stmt->execute([$id]);
    echo "success: Donation deleted successfully";
} catch (Exception $e) {
    echo "error: Delete failed - " . $e->getMessage();
}
?>
