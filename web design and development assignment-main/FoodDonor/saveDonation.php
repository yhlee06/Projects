<?php
session_start();
require '../Common/db.php';

$id = $_POST['id'] ?? null;
$food_name = $_POST['food_name'] ?? 'New Item';
$description = $_POST['description'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$location = $_POST['location'] ?? '';
$status = $_POST['status'] ?? '';
$user_id = $_SESSION['id'];

$image_path = null;
if (isset($_FILES['donation_image']) && $_FILES['donation_image']['error'] == 0) {
    $filename = time() . '_' . basename($_FILES['donation_image']['name']);
    $target = '../public/uploads/' . $filename;
    if (move_uploaded_file($_FILES['donation_image']['tmp_name'], $target)) {
        $image_path = $target;
    }
}

try {
    if ($id) {
        $sql = "UPDATE food_donation SET food_name=?, description=?, quantity=?, expiry_date=?, location=?, status=?, modified_date=NOW()";
        $params = [$food_name, $description, $quantity, $expiry_date, $location, $status];

        if ($image_path) {
            $sql .= ", image=?";
            $params[] = $image_path;
        }

        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($status === 'pickup') { 
            $voucherNames = ['Aeon Voucher', 'Lotus Groceries', 'Jaya Grocer Groceries', 'MYDIN Voucher']; 
            $voucherName = $voucherNames[array_rand($voucherNames)]; 
            $imagePath = '../public/images/supermarket_voucher.jpg';
            $voucherValue = 'RM' . rand(1, 5);
            $rewardStmt = $pdo->prepare(" 
                INSERT INTO reward (user_id, reward_type, voucher_name, reward_image, voucher_value, date_awarded) 
                VALUES (?, 'Supermarket Voucher', ?, ?, ?, CURDATE()) 
            "); 
            $rewardStmt->execute([$user_id, $voucherName, $imagePath, $voucherValue]);

            echo "success: Donation updated. You get $voucherValue $voucherName voucher";
            exit;
        }
        echo "success: Donation post updated successfully";
    }
} catch (Exception $e) {
    echo "error: Save failed - " . $e->getMessage();
}
?>
