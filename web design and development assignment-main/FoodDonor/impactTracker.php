<?php
session_start();
require '../Common/db.php';
require '../Common/accessControl.php';

$user_id = $_SESSION['id'];

$stmt = $pdo->prepare("
  SELECT 
    COUNT(*) AS total_donations,
    SUM(CASE WHEN status = 'pickup' THEN 1 ELSE 0 END) AS total_pickup,
    SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS total_expired,
    SUM(quantity) AS total_quantity
  FROM food_donation
  WHERE user_id = ?
");

$stmt->execute([$user_id]);
$impact = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Impact Tracker</title>
<link href="../Common/sidebar.css" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F3EAD7;
    color: #1E2D24;
}

.main {
    margin-left:0;
    padding: 20px;
}

.main h2 {
  margin-bottom: 20px;
  color: #165540;
  font-size: 22px;
}

.allcards {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.card {
  background: #1F5C44;                 
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 14px;
  box-shadow: 0 4px 12px rgba(22,85,64,0.35);
}

.card h3 {
  margin: 0;
  color: #ffffff;                      
  font-size: 18px;
}

.card p {
  margin: 10px;
  color: #E6EFE9;                     
  font-size: 20px;
  font-weight: bold;
}

@media only screen and (min-width:768px) {
  .main {
    margin-left: 280px;
    padding: 40px;
  }

  .main h2 {
    font-size: 26px;
  }

  .allcards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    max-width: 900px;
  }

  .card {
    padding: 24px;
  }

  .card h3 {
    font-size: 20px;
  }

  .card p {
    font-size: 26px;
  }
}
</style>

</head>
<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">
  <h2>Impact Tracker</h2>
  <div class="allcards">
    <div class="card">
      <h3>Total Donations Posted</h3>
      <p><?= $impact['total_donations'] ?></p>
    </div>

    <div class="card">
      <h3>Food Successfully Picked Up</h3>
      <p><?= $impact['total_pickup'] ?></p>
    </div>

    <div class="card">
      <h3>Expired Donations</h3>
      <p><?= $impact['total_expired'] ?></p>
    </div>

    <div class="card">
      <h3>Total Quantity Donated</h3>
      <p><?= $impact['total_quantity'] ?> items</p>
    </div>
  </div>
</div>

</body>
</html>
