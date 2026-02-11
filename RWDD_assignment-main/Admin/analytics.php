<?php
require_once 'admin_guard.php';
require_once '../common/db.php';


$totalUsers = $pdo
    ->query("SELECT COUNT(*) FROM user WHERE role = 'user' AND status = 'active'")
    ->fetchColumn();


$approvedRecipes = $pdo
    ->query("SELECT COUNT(*) FROM recipe WHERE status = 'approved'")
    ->fetchColumn();

$declinedRecipes = $pdo
    ->query("SELECT COUNT(*) FROM recipe WHERE status = 'declined'")
    ->fetchColumn();

$pendingRecipes = $pdo
    ->query("SELECT COUNT(*) FROM recipe WHERE status = 'pending'")
    ->fetchColumn();

$donationTableExists = false;
$totalDonations = 0;
$totalDonators  = 0;
$totalItemsDonated = 0;

try {
    $check = $pdo->query("SHOW TABLES LIKE 'food_donation'");
    if ($check->rowCount() > 0) {
        $donationTableExists = true;

        $totalDonations = $pdo
            ->query("SELECT COUNT(*) FROM food_donation")
            ->fetchColumn();

        $totalDonators = $pdo
            ->query("SELECT COUNT(DISTINCT user_id) FROM food_donation")
            ->fetchColumn();
        
        $totalItemsDonated = $pdo
            ->query("SELECT SUM(quantity) FROM food_donation")
            ->fetchColumn() ?: 0;
    }
} catch (Exception $e) {

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics</title>

<link href="../Common/sidebar.css" rel="stylesheet">

<style>
body {
    background: #F3EAD7;
    color: #000000ff;
    font-family: Arial, sans-serif;
    margin: 0;
}

.main {
    margin-left: 280px;
    padding: 40px;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.card {
    background: #1F5C44;
    border-radius: 14px;
    padding: 24px;
}

.card h3 {
    margin: 0 0 10px;
    font-size: 16px;
    color: #fafafaff;
}

.card .value {
    font-size: 32px;
    font-weight: bold;
}

.section {
    margin-top: 40px;
}

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">

    <h2>Analytics</h2>
    <p>System overview and usage statistics.</p>


    <div class="section">
        <h3>User Statistics</h3>
        <div class="analytics-grid">
            <div class="card">
                <h3>Total App Users</h3>
                <div class="value"><?= $totalUsers ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Recipe Statistics</h3>
        <div class="analytics-grid">
            <div class="card">
                <h3>Approved Recipes</h3>
                <div class="value"><?= $approvedRecipes ?></div>
            </div>
            <div class="card">
                <h3>Pending Recipes</h3>
                <div class="value"><?= $pendingRecipes ?></div>
            </div>
            <div class="card">
                <h3>Declined Recipes</h3>
                <div class="value"><?= $declinedRecipes ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Food Donation Statistics</h3>
        <div class="analytics-grid">
            <?php if ($donationTableExists): ?>
                <div class="card">
                    <h3>Total Donations</h3>
                    <div class="value"><?= $totalDonations ?></div>
                </div>
                <div class="card">
                    <h3>Total Donators</h3>
                    <div class="value"><?= $totalDonators ?></div>
                </div>
                <div class="card">
                    <h3>Items Donated</h3>
                <div class="value"><?= $totalItemsDonated ?></div>
            </div>

            <?php else: ?>
                <div class="card">
                    <h3>Donation Data</h3>
                    <div class="value" style="font-size:16px; color:#bbb;">
                        Not available yet
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>


</body>
</html>
