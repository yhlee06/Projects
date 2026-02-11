<?php
require '../Common/db.php';

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'];

$limit = 4;
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$start = ($page - 1) * $limit;

$query = "
  SELECT 
    date_donated,
    COUNT(*) AS total_count,
    SUM(CASE WHEN status = 'pickup' THEN 1 ELSE 0 END) AS pickup_count,
    SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS expired_count
  FROM food_donation
  WHERE user_id = ?
  GROUP BY date_donated
  ORDER BY date_donated DESC
  LIMIT $start, $limit
";
$stmt = $pdo->prepare($query); 
$stmt->execute([$user_id]);
$dailyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalResult = $pdo->prepare("SELECT COUNT(DISTINCT date_donated) AS total FROM food_donation WHERE user_id = ?"); 
$totalResult->execute([$user_id]);
$total = $totalResult->fetch(PDO::FETCH_ASSOC)["total"]; 
$totalPages = ceil($total / $limit);
?>