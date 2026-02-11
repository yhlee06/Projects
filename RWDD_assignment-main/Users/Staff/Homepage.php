<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $recipeCount = $pdo->query("SELECT COUNT(*) FROM recipe")->fetchColumn();
    $challengeCount = $pdo->query("SELECT COUNT(*) FROM challenge")->fetchColumn();
    $msgCount = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn(); 
} catch (PDOException $e) {
    $userCount = 35; $recipeCount = 13; $challengeCount = 10; $msgCount = 2;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage | Zero Waste</title>
    <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
<style>
  /* ===== PAGE BASE ===== */
  body { 
    background-color: #F3EAD7; 
    color: #1E2D24; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    margin: 0; 
    overflow-x: hidden;
  }

  .main { 
    margin-left: 260px; 
    padding: 40px; 
    transition: all 0.3s ease;
  }

  /* ===== MOBILE NAV BAR ===== */
  .mobile-nav {
    display: none;
    background: #165540;
    color: white;
    padding: 15px 20px;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .menu-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
  }

  /* ===== HEADER & STATS (Existing styles) ===== */
  .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
  .welcome { font-size: 28px; font-weight: 700; margin: 0; color: #1E2D24; }
  .sub { color: #6B7F75; font-size: 14px; margin-top: 5px; }
  .btn-back { padding: 10px 22px; border-radius: 10px; background: #E6EFE9; border: 2px solid #165540; color: #165540; text-decoration: none; font-size: 14px; font-weight: 700; transition: all 0.3s ease; display: inline-block; }
  .btn-back:hover { background: #165540; color: #ffffff; }

  .stats-row { display: flex; gap: 20px; margin-bottom: 30px; }
  .stat-card { background: #A3CFAE; border-radius: 14px; padding: 26px; flex: 1; border-bottom: 5px solid #165540; box-shadow: 0 6px 14px rgba(22,85,64,0.25); }
  .stat-value { color: #165540; font-size: 34px; font-weight: 800; margin: 0; }
  .stat-label { color: #355F50; font-size: 13px; font-weight: 600; }

  .activity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .activity-box { background: #1F5C44; border-radius: 14px; padding: 26px; min-height: 150px; }
  .box-title { color: #EAF4EE; font-weight: 700; margin-bottom: 10px; font-size: 18px; }

  @media (max-width: 768px) {
    .mobile-nav { display: flex; } /* Show mobile bar */
    
    .main { 
        margin-left: 0; 
        padding: 20px; 
    }

    .sidebar { 
        transform: translateX(-100%); 
        transition: transform 0.3s ease;
        position: fixed;
        z-index: 999;
    }

    .sidebar.active {
        transform: translateX(0);
    }
  }

  @media (max-width: 768px) {
    .stats-row { flex-direction: column; }
    .activity-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
    .btn-back { width: 100%; text-align: center; box-sizing: border-box; }
  }
</style>
</head>
<body>

    <?php include __DIR__ . "/../../Common/sidebar.php"; ?>

    <div class="main">
        <div class="page-header">
            <div>
                <h2 class="welcome">Homepage</h2>
                <p class="sub">Live monitoring of community growth and communications.</p>
            </div>
            <a href="staffDashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <p class="stat-label">Platform Users</p>
                <p class="stat-value"><?= htmlspecialchars($userCount) ?></p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Approved Recipes</p>
                <p class="stat-value"><?= htmlspecialchars($recipeCount) ?></p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Feedback Pending</p>
                <p class="stat-value"><?= htmlspecialchars($msgCount) ?></p>
            </div>
        </div>

        <div class="activity-grid">
            <div class="activity-box">
                <h3 class="box-title"><i class="fas fa-user-plus"></i> Recent Onboarding</h3>
                <p style="color: #EAF4EE; opacity: 0.8;">2 users found.</p>
            </div>
            <div class="activity-box">
                <h3 class="box-title"><i class="fas fa-comment-dots"></i> Recent Feedback</h3>
                <p style="color: #EAF4EE; opacity: 0.8;">4 messages found.</p>
            </div>
        </div>
    </div>

    <script>
        // Simple script to toggle sidebar on mobile
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.querySelector('.sidebar'); // Ensure your sidebar.php uses the class "sidebar"

        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>



