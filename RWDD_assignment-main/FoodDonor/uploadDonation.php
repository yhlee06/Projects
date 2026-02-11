<?php
session_start();
require '../Common/db.php';

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo "error: User not logged in";
    exit;
}

$food_name   = $_POST['food_name'] ?? '';
$description = $_POST['description'] ?? '';
$quantity    = $_POST['quantity'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$location    = $_POST['location'] ?? '';

if (empty($food_name) || empty($description) || empty($quantity) || empty($expiry_date) || empty($location)) {
    echo "error: Please fill in all fields";
    exit;
}

if (!isset($_FILES['donation_image']) || $_FILES['donation_image']['error'] !== UPLOAD_ERR_OK) {
    echo "error: Image upload failed";
    exit;
}

$upload_dir = '../public/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$filename    = basename($_FILES['donation_image']['name']);
$target_path = $upload_dir . time() . '_' . $filename;

if (move_uploaded_file($_FILES['donation_image']['tmp_name'], $target_path)) {
    $date_donated = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO food_donation 
        (user_id, food_name, image, description, quantity, expiry_date, status, date_donated, location, modified_date) 
        VALUES (?, ?, ?, ?, ?, ?, 'active', ?, ?, NOW())");
    $stmt->execute([$user_id, $food_name, $target_path, $description, $quantity, $expiry_date, $date_donated, $location]);

    echo "success";
} else {
    echo "error: Failed to save image";
}
?>
