<?php
session_start();
require '../Common/db.php';
require '../Common/accessControl.php';

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$stmt = $pdo->prepare("SELECT * FROM food_donation WHERE user_id = ? ORDER BY date_donated DESC");
$stmt->execute([$user_id]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History</title>
    <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
<style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F3EAD7;
    color: #1E2D24;
  }

  .box {
    width: 1100px;
    height: 150px;
    background: #1F5C44;
    border-radius: 12px;
    margin-top: 20px;
    padding: 15px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 10px rgba(22,85,64,0.25);
  }

  .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
  }

  .image {
    width: 160px;
    height: 125px;
    border-radius: 10px;
    object-fit: cover;
  }

  /* 🔥 FIX: TEXT COLORS FOR DARK CARD */
  .info {
    flex-grow: 1;
    margin-left: 30px;
    color: #FFFFFF;
  }

  .info h3 {
    margin: 0;
    font-size: 20px;
    color: #FFFFFF;
  }

  .info p {
    margin: 6px 0;
    font-size: 14px;
    color: #E6EFE9;
  }

  .expiry_date {
    font-size: 12px;
    color: #B7DCC0;
    margin-top: 6px;
  }

  .action {
    margin-right: 30px;
  }

  .button {
    width: 140px;
    height: 40px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ff6a6a, #c31432);
    color: white;
    font-weight: bold;
    font-size: 18px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transition: all 0.3s ease;
  }

  .button:hover {
    background: linear-gradient(135deg, #ff9a9e, #f6416c);
    transform: translateY(-2px);
  }

  /* MOBILE (LIGHT CARD — KEEP DARK TEXT) */
  @media only screen and (max-width: 600px) {

    .box {
      width: 93%;
      background: #B7DCC0;
      border-radius: 12px;
      padding: 15px;
      margin-top: 20px;
      box-shadow: 0 3px 8px rgba(22,85,64,0.25);
    }

    .info {
      color: #1E2D24;
      margin-left: 0;
    }

    .info h3 {
      font-size: 18px;
      color: #165540;
    }

    .info p {
      font-size: 15px;
      color: #1E2D24;
    }

    .expiry_date {
      color: #3d5f50;
    }

    .container {
      flex-direction: row;
      gap: 15px;
    }

    .image {
      width: 100px;
      height: 100px;
    }

    .action {
      margin-top: 12px;
      margin-right: 5px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .button {
      width: 100%;
      font-size: 15px;
      padding: 10px;
      border-radius: 25px;
    }
  }
</style>


</head>
<body>
    <?php include '../Common/sidebar.php'; ?>
    <div class="main">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
            <img src="../public/icons/history.png" width="28" height="28">
            <h2 style="margin:0;">Donation History</h2>
        </div>

        <?php if (empty($donations)): ?>
            <div style="text-align:center; margin-top:40px; color:#ccc; font-size:16px;">
                You haven't made any donations yet.<br>
                Click the <strong>"Post New Donation"</strong> button on your dashboard to get started!
            </div>
            <?php endif; ?>

        <?php foreach ($donations as $donation): ?>
            <div class="box">
                <div class="container">
                    <img class="image" src="<?php echo htmlspecialchars($donation['image']); ?>" alt="Food Image">
                    
                    <div class="info">
                        <h3><?php echo htmlspecialchars($donation['food_name']); ?></h3>
                        <p>Quantity: <?php echo $donation['quantity']; ?></p>
                        <p>Status: <?php echo ucfirst($donation['status']); ?></p>
                    </div>

                    <div class="action">
                        <button class="button" onclick="window.location.href='donationDetails.php?id=<?php echo $donation['id']; ?>'">Edit</button>
                        <div class="expiry_date">Expiry Date: <?php echo $donation['expiry_date']; ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>