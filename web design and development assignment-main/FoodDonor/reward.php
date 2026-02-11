<?php
session_start();
require '../Common/db.php';
require '../Common/accessControl.php';

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Fetch rewards for logged-in user
$stmt = $pdo->prepare("SELECT * FROM reward WHERE user_id = ? ORDER BY date_awarded DESC");
$stmt->execute([$user_id]);
$rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Rewards</title>
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
    background: #1F5C44; /* dark green card */
    border-radius: 12px;
    margin-top: 20px;
    padding: 15px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(22,85,64,0.35);
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

.info {
    flex-grow: 1;
    margin-left: 30px;
    color: #ffffff; 
}

.info h3 {
    margin: 0;
    font-size: 20px;
    color: #ffffff;
}

.info p {
    margin: 6px 0;
    font-size: 14px;
    color: #E6EFE9; 
}

.expiry_date {
    font-size: 12px;
    color: #CFE6DA;
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

/* 📱 MOBILE */
@media only screen and (max-width: 600px) {
  .box {
    width: 93%;
    background: #1F5C44;
    border-radius: 12px;
    padding: 15px;
    margin-top: 20px;
    box-shadow: 0 3px 8px rgba(22,85,64,0.35);
  }

  .container {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 15px;
  }

  .image {
    width: 100px;
    height: 100px;
    flex-shrink: 0;
  }

  .info {
    flex-grow: 1;
    margin-left: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .info h3 {
    font-size: 18px;
    margin: 0;
    color: #ffffff;
  }

  .info p {
    font-size: 15px;
    margin: 0;
    color: #E6EFE9;
  }

  .expiry_date {
    font-size: 12px;
    color: #CFE6DA;
    margin-top: 4px;
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
        <img src="../public/icons/reward.png" width="28" height="28">
        <h2 style="margin:0;">My Rewards</h2>
    </div>

    <?php if (empty($rewards)): ?>
        <div style="text-align:center; margin-top:40px; color:#ccc; font-size:16px;">
            You haven't earned any rewards yet.<br>
            Complete challenges or participate in activities to earn rewards!
        </div>
    <?php endif; ?>

    <?php foreach ($rewards as $reward): ?>
        <div class="box">
            <div class="container"> 
                    <img class="image" src="<?php echo htmlspecialchars($reward['reward_image']); ?>" alt="Reward Image">

               
                <div class="info">
                    <h3><?php echo htmlspecialchars($reward['voucher_name']); ?></h3>
                    <p>Type: <?php echo htmlspecialchars($reward['reward_type']); ?></p>
                    <p>Value: <?php echo htmlspecialchars($reward['voucher_value']); ?></p>
                </div>

                <div class="action">
                    <button class="button" onclick="alert('Redeem feature coming soon!')">Redeem</button>
                    <div class="expiry_date">Date Awarded: <?php echo $reward['date_awarded']; ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
