<?php
require '../Common/db.php';

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'];

$today = new DateTime();
$startDate = new DateTime('-6 days'); 
$startDateStr = $startDate->format('d-m-Y'); 
$endDateStr = $today->format('d-m-Y');

$stmt = $pdo->prepare("SELECT * FROM food_donation WHERE user_id = ? AND date_donated BETWEEN ? AND ?");
$stmt->execute([
    $user_id, 
    $startDate->format('Y-m-d'), 
    $today->format('Y-m-d')
]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$activeCount = 0;
$pickupCount = 0;
$expiredCount = 0;
$activePosts = [];
$pickupPosts = [];
$expiredPosts = [];

foreach ($donations as $donation) {
    switch (strtolower($donation['status'])) {
        case 'active':
            $activeCount++;
            $activePosts[] = $donation;
            break;
        case 'pickup':
            $pickupCount++;
            $pickupPosts[] = $donation;
            break;
        case 'expired':
            $expiredCount++;
            $expiredPosts[] = $donation;
            break;
    }
}
?>
