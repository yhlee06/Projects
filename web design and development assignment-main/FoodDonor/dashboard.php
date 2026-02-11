<?php
session_start();

if (!isset($_SESSION['id'], $_SESSION['username'])) {
    die('You are not logged in.');
}

require '../Common/db.php';
require '../Common/accessControl.php';
require __DIR__ . '/weekDonation.php';
require __DIR__ . '/pagination.php';

// Safe defaults to prevent undefined variable errors
$startDateStr = $startDateStr ?? 'N/A';
$endDateStr   = $endDateStr ?? 'N/A';
$activeCount  = $activeCount ?? 0;
$pickupCount  = $pickupCount ?? 0;
$expiredCount = $expiredCount ?? 0;
$dailyStats   = $dailyStats ?? [];
$totalPages   = $totalPages ?? 1;
$page         = $page ?? 1;

$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Donor Dashboard</title>
  <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
<style>
  /* Mobile-first default styles */
  body {
    margin: 0;
    padding: 0;
    background: #F3EAD7;
    color: #1E2D24;
    font-family: Arial, sans-serif;
  }

  .main {
    margin-left: 0;
    padding: 20px;
    padding-bottom: 80px;
  }

  .header {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  h1 {
    font-size: 20px;
    margin: 20px 0 10px;
    color: #165540;
  }

  /* ADD BUTTON */
  .add-btn {
    width: 60%;
    margin: 0 auto;
    text-align: center;
    font-size: 15px;
    padding: 12px;
    border-radius: 25px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    box-shadow: 0 4px 12px rgba(22, 85, 64, 0.35);
    transition: 0.3s ease;
  }

  .action-row{
  display:flex;
  gap:16px;
  align-items:center;
  }

/* QR Scanner button */
  .qr-btn{
    padding:12px 18px;
    border-radius:25px;
    border:none;
    font-weight:bold;
    cursor:pointer;
    background: linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    box-shadow:0 4px 12px rgba(37,99,235,0.35);
  }
  .qr-btn:hover{
    background:linear-gradient(135deg,#1d4ed8,#3b82f6);
  }

  .add-btn:hover {
    background: linear-gradient(135deg, #1e7a5a, #3ee38f);
    transform: translateY(-2px);
  }

  h2 {
    margin-top: 30px;
    color: #165540;
  }

  /* TABS */
  .tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 10px;
  }

  .tab {
    flex: 1 1 25%;
    font-size: 14px;
    padding: 12px;
    border-radius: 20px;
    text-align: center;
    font-weight: bold;
    background: #A3CFAE;
    color: #1E2D24;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  }

  /* TABLE */
  table {
    width: 100%;
    font-size: 13px;
    margin-top: 25px;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    background: #E6EFE9;
  }

  th, td {
    padding: 12px;
    text-align: center;
  }

  th {
    background: #165540;
    color: white;
    font-size: 14px;
    font-weight: bold;
  }

  td {
    background: #A3CFAE;
    font-size: 13px;
    color: #1E2D24;
  }

  tr:nth-child(even) td {
    background: #B7DCC0;
  }

  td:first-child {
    font-weight: bold;
    color: #165540;
  }

  /* PAGINATION */
  .pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
  }

  .pagination a {
    padding: 8px 12px;
    font-size: 13px;
    border-radius: 6px;
    background: #E6EFE9;
    color: #165540;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    border: 1px solid #B7DCC0;
  }

  .pagination a:hover {
    background: #165540;
    color: white;
    transform: translateY(-2px);
    border-color: transparent;
  }

  .pagination a.active {
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(22, 85, 64, 0.35);
  }

  /* DESKTOP */
  @media only screen and (min-width: 768px) {
    .main {
      margin-left: 280px;
      padding: 40px;
    }

    h1 {
      font-size: 26px;
    }

    .add-btn {
      width: 20%;
      margin: 0;
    }

    .tabs {
      gap: 150px;
      flex-wrap: nowrap;
    }

    .tab {
      font-size: 16px;
      padding: 12px 22px;
      border-radius: 30px;
    }

    table {
      width: 80%;
      margin: 30px auto;
      box-shadow: 0 4px 12px rgba(22, 85, 64, 0.25);
    }

    .pagination {
      margin-top: 30px;
    }

    .action-row {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    }

    .qr-btn {
    width: 20%;
    }

  }
</style>

</head>
<body>
  <?php include '../Common/sidebar.php'; ?>

  <div class="main">
    <div class="header">
      <h1>Hello, <?php echo htmlspecialchars($user['name']); ?>!</h1>
      <div class="action-row">
      <button class="add-btn" onclick="window.location.href='donationPost.php'">Post New Donation</button>
      <button class="qr-btn" onclick="window.location.href='qr_scanner.php'">Scan QR</button>
      </div>
      <h2>This Week's Donations (<?php echo $startDateStr; ?> to <?php echo $endDateStr; ?>)</h2>
      
    </div>

    <div class="tabs">
      <div class="tab" style="background: linear-gradient(135deg, #a8e063, #56ab2f);">Active: <?= $activeCount ?></div>
      <div class="tab" style="background: linear-gradient(135deg, #fceabb, #f8b500);">Pickup: <?= $pickupCount ?></div>
      <div class="tab" style="background: linear-gradient(135deg, #ff6a6a, #c31432);">Expired: <?= $expiredCount ?></div>
    </div>

    <div>
      <h2>Pickup & Expired Summary Status</h2>
      <table>
        <tr>
          <th>Donation Date</th>
          <th>Total</th>
          <th>Pickup</th>
          <th>Expired</th>
        </tr>
        <?php if (count($dailyStats) > 0): ?>
          <?php foreach ($dailyStats as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row["date_donated"]) ?></td>
            <td><?= $row["total_count"] ?></td>
            <td><?= $row["pickup_count"] ?></td>
            <td><?= $row["expired_count"] ?></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">No donation records found.</td></tr>
        <?php endif; ?>
      </table>

      <?php if (!empty($dailyStats) && $totalPages > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $page == $i ? "active" : "" ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
