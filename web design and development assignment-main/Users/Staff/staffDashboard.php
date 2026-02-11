<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $recipeCount = $pdo->query("SELECT COUNT(*) FROM recipe")->fetchColumn();
    $challengeCount = $pdo->query("SELECT COUNT(*) FROM challenge")->fetchColumn();
    $msgCount = $pdo->query("SELECT COUNT(*) FROM feedback WHERE status = 'unread'")->fetchColumn();
} catch (PDOException $e) {
    // Fallback data for testing
    $userCount = 35; $recipeCount = 13; $challengeCount = 1; $msgCount = 2;
}

// Logic to determine badges
$badges = [];
$badges[] = ['text' => 'Verified Staff', 'class' => 'badge-staff', 'icon' => '🛡️'];

if ($recipeCount > 10) {
    $badges[] = ['text' => 'Content Master', 'class' => 'badge-curator', 'icon' => '⭐'];
}
if ($msgCount == 0) {
    $badges[] = ['text' => 'Fast Responder', 'class' => 'badge-active', 'icon' => '⚡'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard | Zero Waste</title>
  <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

  <style>
    /* DESKTOP STYLES */
    body { 
        background-color: #F3EAD7; 
        color: #165540; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        margin: 0; 
    }
    
    .main { margin-left: 260px; padding: 40px; transition: margin 0.3s ease; }
    
    .welcome-section { margin-bottom: 30px; }
    .welcome-title { font-size: 24px; font-weight: bold; margin: 0; }
    .welcome-subtext { color: #165540; font-size: 14px; margin-bottom: 20px; }

    /* PROFILE & BADGES */
    .profile-banner {
        background: #1E2D24;
        padding: 25px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        border-left: 5px solid #00cc99;
    }
    .profile-icon { font-size: 50px; }

    .badge-container {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: transform 0.2s;
    }
    .badge:hover { transform: scale(1.05); }
    
    .badge-staff { background: #e3f2fd; color: #0d47a1; border: 1px solid #2196f3; }
    .badge-curator { background: #fff3e0; color: #e65100; border: 1px solid #ff9800; }
    .badge-active { background: #e8f5e9; color: #1b5e20; border: 1px solid #4caf50; }

    /* STATS CARDS */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: #ffffff;
        color: #000;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .stat-card h4 { margin: 0; font-size: 11px; color: #666; text-transform: uppercase; }
    .stat-card .value { font-size: 22px; font-weight: bold; margin-top: 5px; }

    /* GRID LAYOUT */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 20px;
    }

    .info-box {
        background: #ffffff;
        color: #000;
        border-radius: 15px;
        padding: 20px;
        min-height: 200px;
    }
    .info-box h3 { margin-top: 0; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    
    .activity-list { list-style: none; padding: 0; margin: 0; }
    .activity-list li { padding: 10px 0; font-size: 14px; border-bottom: 1px solid #f0f0f0; color: #444; }

    .action-links { display: flex; flex-direction: column; gap: 10px; margin-top: 15px; }
    .action-btn {
        background: #f8f8f8;
        color: #333;
        text-decoration: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid #ddd;
        text-align: center;
        transition: 0.2s;
    }
    .action-btn:hover { background: #00cc99; color: white; border-color: #00cc99; }

    /* MOBILE VIEW */
    @media (max-width: 1024px) {
      .main { margin-left: 0; padding: 20px; }
      .stats-row { grid-template-columns: repeat(2, 1fr); }
      .content-grid { grid-template-columns: 1fr; }
      .profile-banner { flex-direction: column; text-align: center; }
      .badge-container { justify-content: center; }
    }

    @media (max-width: 480px) {
      .stats-row { grid-template-columns: 1fr; }
    }


    .welcome {
      font-size: 20px;
    }
    .action-btn:hover { background: #00cc99; color: white; }

    @media (max-width: 1000px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
        .main { margin-left: 0; padding: 20px; }

    .btn-action {
      display: block;
      width: 100%;
      margin: 8px 0 0 0;
      text-align: center;
    }

    .grid {
      grid-template-columns: 1fr;
    }

    .card {
      padding: 16px;
    }
  }

  @media (max-width: 420px) {
    .welcome { font-size: 18px; }
    .sub { font-size: 13px; }
    .card h3 { font-size: 16px; }
  }
</style>
</head>

<body>
  <?php include __DIR__ . "/../../Common/sidebar.php"; ?>
  
  <div class="main">
    <div class="welcome-section">
        <h2 class="welcome-title">Welcome, <?php echo htmlspecialchars($user['name'] ?? 'Devenash Rao'); ?> 👋</h2>
        <p class="welcome-subtext">Manage your recipes, challenges, and system performance.</p>
    </div>

    <div class="profile-banner">
        <div class="profile-icon">👤</div>
        <div>
            <div style="font-weight: bold; font-size: 20px;">Staff Management Profile</div>
            <div style="color: #00cc99; font-size: 14px;">Zero Waste Administrator</div>
            
            <div class="badge-container">
                <?php foreach ($badges as $badge): ?>
                    <span class="badge <?= $badge['class'] ?>">
                        <?= $badge['icon'] ?> <?= $badge['text'] ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <h4>Total Users</h4>
            <div class="value"><?= $userCount ?></div>
        </div>
        <div class="stat-card">
            <h4>Recipes Managed</h4>
            <div class="value"><?= $recipeCount ?></div>
        </div>
        <div class="stat-card">
            <h4>Active Challenges</h4>
            <div class="value"><?= $challengeCount ?></div>
        </div>
        <div class="stat-card">
            <h4>Unread Messages</h4>
            <div class="value" style="<?= $msgCount > 0 ? 'color: #e74c3c;' : '' ?>"><?= $msgCount ?></div>
        </div>
        <div class="stat-card">
            <h4>System Status</h4>
            <div class="value" style="color: #00cc99;">Online</div>
        </div>
    </div>

    <div class="content-grid">
        <div class="info-box">
            <h3>Recent System Activity</h3>
            <ul class="activity-list">
                <li>• New recipe submitted: "Eco-friendly Pasta"</li>
                <li>• User "Vaishumita" joined a new challenge.</li>
                <li>• <?= $msgCount ?> New feedback messages pending review.</li>
                <li>• System backup completed successfully.</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>Quick Management</h3>
            <div class="action-links">
                <a href="likeRecipe.php" class="action-btn">📝 Manage Recipes</a>
                <a href="joinChallenge.php" class="action-btn">🏆 Manage Challenges</a>
                <a href="feedback.php" class="action-btn">✉️ View Messages (<?= $msgCount ?>)</a>
                <a href="Homepage.php" class="action-btn">🌐 View Public Site</a>
            </div>
        </div>
    </div>
  </div>
</body>
</html>

