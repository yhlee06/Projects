<?php
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$userId = (int)$user['id'];

/* =========================
   SUBMIT FEEDBACK
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message"])) {
    $message = trim($_POST["message"]);

    if ($message !== "") {
        $stmt = $pdo->prepare("
            INSERT INTO feedback (user_id, message, status, created_at)
            VALUES (?, ?, 'pending', NOW())
        ");
        $stmt->execute([$userId, $message]);
    }

    header("Location: feedback.php");
    exit;
}

/* =========================
   GET MY FEEDBACK HISTORY
========================= */
$stmt = $pdo->prepare("
    SELECT id, message, admin_reply, status, created_at
    FROM feedback
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback | Zero Waste</title>
  <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

  <style>
    /* ===== PAGE BASE ===== */
    body { background:#F3EAD7; font-family:'Segoe UI', Arial, sans-serif; margin:0; color:#1E2D24; overflow-x: hidden; }
    
    .main { 
        margin-left:260px; 
        padding:24px 32px; 
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

    .content-wrap {
        max-width: 800px;
        margin: 0 auto;
    }

    h1 { margin:0; color:#165540; font-size:34px; font-weight:800; }
    p.sub { margin:6px 0 18px; color:#4b6b5a; font-size:14px; }

    /* ===== CARDS & BUTTONS ===== */
    .card{
      background:#E6EFE9;
      border-left:6px solid #165540;
      border-radius:14px;
      padding:20px;
      margin-bottom:16px;
      box-shadow:0 4px 10px rgba(22,85,64,0.12);
    }

    textarea{
      width:100%;
      padding:15px;
      border-radius:10px;
      border:1px solid #88B393;
      background:#fff;
      resize:none;
      font-size:16px; 
      box-sizing:border-box;
      display: block;
    }

    .btn{
      margin-top:12px;
      background:linear-gradient(135deg,#165540,#2ECC71);
      color:#fff;
      border:none;
      padding:14px 24px;
      border-radius:12px;
      cursor:pointer;
      font-weight:800;
      width: auto;
      transition: transform 0.2s;
    }

    .btn:active { transform: scale(0.98); }

    .meta{
      display:flex;
      flex-wrap: wrap;
      gap:10px;
      align-items:center;
      margin-top:12px;
      font-size:12px;
      color:#4b6b5a;
    }

    .badge{
      padding:4px 12px;
      border-radius:999px;
      font-weight:800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .pending{ background:#FFD966; color:#1E2D24; }
    .replied{ background:#2ECC71; color:white; }

    .reply{
      margin-top:15px;
      background:#fff;
      border:1px solid #B7DCC0;
      padding:15px;
      border-radius:12px;
      font-size:14px;
      position: relative;
    }

    /* ===== MOBILE RESPONSIVE OVERRIDES ===== */
    @media (max-width: 992px){
      .mobile-nav { display: flex; }

      .main{ 
          margin-left:0; 
          padding:20px 15px; 
      }
      
      /* Sidebar hidden off-screen by default on mobile */
      .sidebar { 
          transform: translateX(-100%); 
          position: fixed;
          z-index: 1001;
          transition: transform 0.3s ease;
          display: block !important; /* Overriding your previous 'display:none' */
      }

      /* When the toggle script adds the 'active' class */
      .sidebar.active {
          transform: translateX(0);
      }

      h1 { font-size: 28px; }
      
      .btn { 
          width: 100%; 
          font-size: 16px;
      }
    }
  </style>
</head>
<body>

<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
    <div class="content-wrap">
        <h1>Feedback</h1>
        <p class="sub">Send feedback to admin and track the reply status.</p>

        <div class="card">
            <form method="POST">
                <textarea name="message" rows="4" placeholder="Write your feedback here..." required></textarea>
                <button class="btn" type="submit">Send Feedback</button>
            </form>
        </div>

        <h2 style="font-size: 18px; color: #165540; margin: 25px 0 10px 5px;">History</h2>
        
        <?php if (empty($rows)): ?>
            <div class="card" style="text-align: center; color: #6B7F75;">
                No feedback submitted yet.
            </div>
        <?php else: ?>
            <?php foreach ($rows as $r): ?>
                <div class="card">
                    <div style="font-weight:800; color:#165540; display: flex; justify-content: space-between;">
                        <span>Your Message</span>
                    </div>
                    <div style="margin-top:8px; line-height: 1.5;"><?= htmlspecialchars($r['message']) ?></div>

                    <div class="meta">
                        <span class="badge <?= $r['status']==='replied' ? 'replied' : 'pending' ?>">
                            <?= ucfirst($r['status']) ?>
                        </span>
                        <span><?= date("d M Y, H:i", strtotime($r['created_at'])) ?></span>
                    </div>

                    <?php if (!empty($r['admin_reply'])): ?>
                        <div class="reply">
                            <div style="font-weight:800; color:#165540; margin-bottom: 5px;">Admin Reply</div>
                            <div style="line-height: 1.5;"><?= htmlspecialchars($r['admin_reply']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Script to handle sidebar toggle on mobile
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.querySelector('.sidebar');

    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside of it
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('active') && !sidebar.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }
</script>

</body>
</html>


