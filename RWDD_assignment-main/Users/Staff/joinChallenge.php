<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

$user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Error: User session not found. Please log in again.");
}

// Handle joining a challenge
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_now'])) {
    $challenge_id = (int)$_POST['challenge_id'];
    try {
        $check = $pdo->prepare("SELECT id FROM user_challenge WHERE user_id = ? AND challenge_id = ?");
        $check->execute([$user_id, $challenge_id]);
        
        if (!$check->fetch()) {
            $ins = $pdo->prepare("INSERT INTO user_challenge (user_id, challenge_id) VALUES (?, ?)");
            $ins->execute([$user_id, $challenge_id]);
            $msg = "Successfully joined the challenge!";
        }
    } catch (PDOException $e) { $msg = "Error: " . $e->getMessage(); }
}

// Fetch challenges and check if current user has joined them
try {
    $stmt = $pdo->prepare("
        SELECT c.*, 
        (SELECT COUNT(*) FROM user_challenge uc WHERE uc.challenge_id = c.id AND uc.user_id = ?) as joined 
        FROM challenge c ORDER BY c.id DESC
    ");
    $stmt->execute([$user_id]);
    $challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $challenges = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges | Zero Waste</title>
    <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
<style>
  /* ===== PAGE BASE ===== */
  body { background: #F3EAD7; color: #1E2D24; font-family: 'Segoe UI', sans-serif; margin: 0; overflow-x: hidden; }
  
  .main { 
      margin-left: 260px; /* Aligns with your desktop sidebar width */
      padding: 40px; 
      transition: all 0.3s ease; 
  }

  /* ===== MOBILE TOP BAR ===== */
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
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }

  .menu-toggle {
      background: none;
      border: none;
      color: white;
      font-size: 22px;
      cursor: pointer;
  }

  /* ===== BADGE STYLES ===== */
  .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      margin-bottom: 8px;
      letter-spacing: 0.5px;
  }
  .badge-joined { background: #2ECC71; color: white; }
  .badge-open { background: #FFD966; color: #1E2D24; }
  .badge-points { background: rgba(255,255,255,0.2); color: #F3EAD7; border: 1px solid rgba(255,255,255,0.3); }

  /* ===== CARD STYLES ===== */
  .card { 
    background: #1F5C44; 
    padding: 22px; 
    border-radius: 16px; 
    margin-bottom: 18px; 
    border-left: 6px solid #165540; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    box-shadow: 0 6px 14px rgba(22,85,64,0.25);
  }

  .btn-join { 
      background: linear-gradient(135deg, #165540, #2ECC71); 
      color: #fff; border: none; padding: 10px 30px; 
      border-radius: 24px; font-weight: 700; cursor: pointer; 
      transition: 0.3s; 
  }
  .btn-joined { background: #355F50; color: #A3CFAE; cursor: default; }

  /* Status Message */
  .status-msg { background: #165540; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; }

  /* ===== MOBILE RESPONSIVE OVERRIDES ===== */
  @media (max-width: 992px) {
    .mobile-nav { display: flex; }

    .main { 
        margin-left: 0; 
        padding: 20px 15px; 
    }
    
    /* Sidebar Slide-in Logic */
    .sidebar { 
        transform: translateX(-100%); 
        position: fixed;
        z-index: 1001;
        transition: transform 0.3s ease;
        display: block !important;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .card { 
        flex-direction: column; 
        align-items: flex-start; 
        gap: 14px; 
    }
    
    .btn-join { width: 100%; text-align: center; padding: 14px; }
  }
</style>
</head>
<body>

<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
    <h2 style="color: #165540;"><i class="fas fa-award"></i> Zero Waste Challenges</h2>
    <p style="color: #6B7F75; margin-bottom: 30px;">Complete goals to earn cafeteria rewards.</p>

    <?php if (isset($msg)): ?>
        <div class="status-msg"><?= $msg ?></div>
    <?php endif; ?>
    
    <?php if (empty($challenges)): ?>
        <p>No active challenges found.</p>
    <?php else: ?>
        <?php foreach ($challenges as $c): ?>
            <div class="card">
                <div style="width: 100%;">
                    <div>
                        <?php if ($c['joined'] > 0): ?>
                            <span class="badge badge-joined"><i class="fas fa-check-circle"></i> Joined</span>
                        <?php else: ?>
                            <span class="badge badge-open"><i class="fas fa-unlock"></i> Open</span>
                        <?php endif; ?>
                        <span class="badge badge-points">+<?= $c['points_reward'] ?> Points</span>
                    </div>

                    <h3 style="margin:0; color: #EAF4EE;"><?= htmlspecialchars($c['challenge_name']) ?></h3>
                    <p style="color:#D7E9DF; font-size:14px; margin: 10px 0;"><?= htmlspecialchars($c['description']) ?></p>
                </div>

                <form method="POST" style="width: 100%; text-align: right;">
                    <input type="hidden" name="challenge_id" value="<?= $c['id'] ?>">
                    <?php if ($c['joined'] > 0): ?>
                        <button type="button" class="btn-join btn-joined">Joined</button>
                    <?php else: ?>
                        <button type="submit" name="join_now" class="btn-join">Join Now</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    // Standardized Mobile Toggle Logic
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.querySelector('.sidebar');

    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('active') && !sidebar.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }
</script>

</body>
</html>