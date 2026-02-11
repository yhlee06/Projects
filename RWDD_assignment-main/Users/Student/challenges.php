<?php
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$userId = (int) ($user["id"] ?? 0);
if ($userId <= 0) {
  header("Location: /RWDD_assignment/LoginSignUp/login.php");
  exit;
}

/* =========================
   JOIN CHALLENGE (POST) + AUTO BADGE
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["join_id"])) {
    $challengeId = (int) $_POST["join_id"];
    $userId = (int) ($user["id"] ?? 0);

    if ($userId > 0 && $challengeId > 0) {

        // 1) Join challenge (avoid duplicates)
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO user_challenge (user_id, challenge_id) VALUES (?, ?)"
        );
        $stmt->execute([$userId, $challengeId]);

        // 2) Check if badge already exists for this challenge
        $check = $pdo->prepare(
            "SELECT id FROM badge WHERE user_id = ? AND challenge_id = ? LIMIT 1"
        );
        $check->execute([$userId, $challengeId]);
        $hasBadge = $check->fetchColumn();

        if (!$hasBadge) {

            // 3) Get challenge name (for badge title)
            $cStmt = $pdo->prepare(
                "SELECT challenge_name FROM challenge WHERE id = ? LIMIT 1"
            );
            $cStmt->execute([$challengeId]);
            $challengeName = $cStmt->fetchColumn();

            if (!$challengeName) {
                $challengeName = "Challenge";
            }

            // 4) Prepare badge details
            $badgeName = $challengeName . " Badge";
            $badgeDesc = "Awarded for joining " . $challengeName;

            // use ONE generic badge icon (simple & safe)
            $iconImage = "../public/icons/badge1.png";

            // 5) Insert badge (AUTO AWARD)
            $ins = $pdo->prepare("
                INSERT INTO badge 
                (user_id, challenge_id, badge_name, description, icon_image, date_awarded)
                VALUES (?, ?, ?, ?, ?, CURDATE())
            ");
            $ins->execute([
                $userId,
                $challengeId,
                $badgeName,
                $badgeDesc,
                $iconImage
            ]);
        }
    }

    header("Location: challenges.php?joined=1");
    exit;
}

/* =========================
   GET ALL CHALLENGES
========================= */
$stmt = $pdo->query("
  SELECT c.*, u.username
  FROM challenge c
  LEFT JOIN user u ON c.created_by = u.id
  ORDER BY c.start_date DESC
");
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   GET JOINED CHALLENGES IDS
========================= */
$stmt = $pdo->prepare("SELECT challenge_id FROM user_challenge WHERE user_id = ?");
$stmt->execute([$userId]);
$joinedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

$joinedMap = array_flip(array_map('intval', $joinedIds));
$joinedCount = count($joinedIds);

/* =========================
   GET JOINED CHALLENGES LIST (for panel)
========================= */
$joinedChallenges = [];
foreach ($challenges as $c) {
  $cid = (int)$c["id"];
  if (isset($joinedMap[$cid])) {
    $joinedChallenges[] = $c;
  }
}

/* =========================
   GET BADGES COLLECTED (from badge table)
========================= */
$stmt = $pdo->prepare("
  SELECT b.*
  FROM badge b
  WHERE b.user_id = ?
  ORDER BY b.date_awarded DESC
");
$stmt->execute([$userId]);
$badges = $stmt->fetchAll(PDO::FETCH_ASSOC);

$badgeCount = count($badges);

$showCongrats = isset($_GET["joined"]) && $_GET["joined"] == "1";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Challenges</title>

<link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

<style>
  :root{
    --cream:#F3EAD7;
    --card:#1f5f47;
    --mint:#00d29a;
    --deep:#165540;
    --text:#0b3f2f;
    --shadow:0 14px 30px rgba(0,0,0,.12);
    --radius:18px;
  }

  *{box-sizing:border-box;}
  html, body{margin:0;padding:0;}

  body{
    background:var(--cream);
    font-family:'Segoe UI', Arial, sans-serif;
    color:var(--text);
    overflow-x:hidden;
  }

  .main{
    margin-left:260px;
    padding:38px 40px 60px;
  }

  .topbar{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
    margin-bottom:18px;
    flex-wrap:wrap;
  }
  .title{
    font-size:34px;
    font-weight:900;
    color:#0b3f2f;
    display:flex;
    align-items:center;
    gap:10px;
  }
  .subtitle{
    margin-top:6px;
    color:#5c7f72;
    font-size:13px;
  }

  .progress-btn{
    border:0;
    cursor:pointer;
    background:var(--deep);
    color:#fff;
    font-weight:900;
    padding:12px 16px;
    border-radius:999px;
    box-shadow:var(--shadow);
    display:flex;
    align-items:center;
    gap:10px;
    white-space:nowrap;
    transition:.2s;
  }
  .progress-btn:hover{filter:brightness(1.05)}
  .pill{
    background:rgba(255,255,255,.18);
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
  }

  .congrats{
    background:#eafff6;
    border-left:6px solid var(--mint);
    border-radius:14px;
    padding:12px 14px;
    box-shadow:0 8px 18px rgba(0,0,0,.08);
    margin:10px 0 18px;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }
  .congrats b{color:var(--deep)}
  .congrats small{display:block;color:#467467;margin-top:4px}
  .congrats .x{
    border:0;background:transparent;
    font-size:18px;cursor:pointer;color:#0b3f2f;
  }

  .card{
    background:var(--card);
    border-radius:var(--radius);
    padding:22px 24px;
    box-shadow:var(--shadow);
    margin:18px 0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:18px;
  }
  .left{
    display:flex;
    gap:16px;
    align-items:center;
    min-width:0;
  }
  .thumb{
    width:92px;
    height:72px;
    border-radius:14px;
    object-fit:cover;
    background:#b7dcc0;
    border:1px solid rgba(255,255,255,.18);
    flex:0 0 auto;
  }
  .content{min-width:0;}
  .content h3{
    color:var(--mint);
    font-size:22px;
    margin-bottom:8px;
    font-weight:900;
  }
  .content p{
    color:#e8fff6;
    opacity:.95;
    margin-bottom:10px;
    font-size:13px;
    line-height:1.35;
  }
  .meta{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    align-items:center;
  }
  .meta span{
    background:rgba(255,255,255,.10);
    color:#eafff6;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    border:1px solid rgba(255,255,255,.12);
    display:inline-flex;
    align-items:center;
    gap:8px;
  }

  /* ✅ PNG icons */
  .ico{
    width:16px;
    height:16px;
    object-fit:contain;
    display:inline-block;
  }

  .reward{
    color:#bff5df;
    font-weight:900;
    margin-top:10px;
    font-size:13px;
    display:flex;
    align-items:center;
    gap:8px;
  }

  .btn{
    border:0;
    cursor:pointer;
    background:#0f6b4d;
    color:#fff;
    font-weight:900;
    padding:12px 18px;
    border-radius:999px;
    min-width:120px;
    transition:.2s;
  }
  .btn:hover{filter:brightness(1.06)}
  .btn.joined{
    background:transparent;
    border:2px solid rgba(255,255,255,.35);
    color:#eafff6;
    cursor:default;
  }

  .overlay{
    position:fixed; inset:0;
    background:rgba(0,0,0,.35);
    display:none;
    z-index:1000;
  }
  .overlay.show{display:block;}

  .panel{
    position:fixed;
    top:0; right:-420px;
    width:420px; max-width:92vw;
    height:100vh;
    background:#f4fff9;
    box-shadow:-16px 0 40px rgba(0,0,0,.20);
    transition:right .25s ease;
    padding:18px;
    display:flex;
    flex-direction:column;
    z-index:1001;
  }
  .panel.show{right:0;}

  .panelhead{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:12px;
  }
  .panelhead h2{font-size:20px;color:#0b3f2f;}
  .close{
    border:0;background:transparent;font-size:22px;cursor:pointer;color:#0b3f2f;
  }

  .tabs{display:flex;gap:10px;margin:10px 0 14px;}
  .tab{
    border:0; cursor:pointer;
    padding:10px 12px;
    border-radius:12px;
    background:#e7f5ee;
    font-weight:900;
    color:#0b3f2f;
    flex:1;
  }
  .tab.active{background:#0f6b4d;color:#fff;}

  .list{
    overflow:auto;
    padding-right:6px;
  }
  .item{
    background:#ffffff;
    border-radius:14px;
    padding:12px 12px;
    box-shadow:0 8px 18px rgba(0,0,0,.08);
    margin-bottom:10px;
    border-left:5px solid #0f6b4d;
    display:flex;
    gap:12px;
    align-items:flex-start;
  }
  .item .name{font-weight:900;color:#0b3f2f;}
  .item .meta2{color:#567a6c;font-size:13px;margin-top:4px;}
  .muted{opacity:.6;border-left-color:#9ca3af;background:#eef2f0;}

  .badgeIcon{
    width:42px;height:42px;
    border-radius:12px;
    object-fit:cover;
    background:#e7f5ee;
    border:1px solid rgba(0,0,0,.08);
    flex:0 0 auto;
  }

  /* MOBILE FIX (force override) */
  @media (max-width: 900px) {
    .main{
      margin-left:0 !important;
      padding:20px 16px 40px !important;
    }
    .card{
      flex-direction:column;
      align-items:flex-start;
    }
    .btn{width:100%;}
  }
</style>
</head>

<body>
<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">

  <div class="topbar">
    <div>
      <div class="title">Zero Waste Challenges</div>
      <div class="subtitle">Complete goals to earn points + badges.</div>
    </div>

    <button class="progress-btn" id="openProgress" type="button">
      My Progress
      <span class="pill">Joined: <?= (int)$joinedCount ?></span>
      <span class="pill">Badges: <?= (int)$badgeCount ?></span>
    </button>
  </div>

  <?php if ($showCongrats): ?>
    <div class="congrats" id="congratsBox">
      <div>
        <b>🎉 Joined successfully!</b>
        <small>Open <b>My Progress</b> to see your joined challenges and badges.</small>
      </div>
      <button class="x" type="button" onclick="document.getElementById('congratsBox').style.display='none'">✕</button>
    </div>
  <?php endif; ?>

  <?php if (empty($challenges)): ?>
    <div class="congrats"><div><b>No challenges available.</b><small>Admin has not created any challenges yet.</small></div></div>
  <?php else: ?>
    <?php foreach ($challenges as $c): ?>
      <?php
        $cid = (int)$c["id"];
        $joined = isset($joinedMap[$cid]);

        $img = "";
        if (!empty($c["image"])) {
          $img = "/RWDD_assignment/public/images/" . $c["image"];
        }

        $name = $c["challenge_name"] ?? "Challenge";
        $desc = $c["description"] ?? "";
        $start = $c["start_date"] ?? "";
        $end = $c["end_date"] ?? "";
        $pts = (int)($c["points_reward"] ?? 0);

        $calendarPng = "/RWDD_assignment/public/icons/calendar.png";
        $starPng = "/RWDD_assignment/public/icons/star.png";
      ?>
      <div class="card">
        <div class="left">
          <?php if ($img): ?>
            <img class="thumb" src="<?= htmlspecialchars($img) ?>" alt="">
          <?php else: ?>
            <div class="thumb"></div>
          <?php endif; ?>

          <div class="content">
            <h3><?= htmlspecialchars($name) ?></h3>
            <p><?= htmlspecialchars($desc) ?></p>

            <div class="meta">
              <span>
                <img class="ico" src="<?= htmlspecialchars($calendarPng) ?>" alt="">
                <?= htmlspecialchars($start) ?> → <?= htmlspecialchars($end) ?>
              </span>
              <span>
                <img class="ico" src="<?= htmlspecialchars($starPng) ?>" alt="">
                <?= (int)$pts ?> pts
              </span>
            </div>

            <div class="reward">
              Reward:
              <img class="ico" src="<?= htmlspecialchars($starPng) ?>" alt="">
              <?= (int)$pts ?> Points
            </div>
          </div>
        </div>

        <?php if (!$joined): ?>
          <form method="POST" style="margin:0;width:100%;">
            <input type="hidden" name="join_id" value="<?= $cid ?>">
            <button class="btn" type="submit">Join Now</button>
          </form>
        <?php else: ?>
          <button class="btn joined" type="button" disabled>Joined</button>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

<!-- Progress Panel -->
<div class="overlay" id="overlay"></div>

<div class="panel" id="panel">
  <div class="panelhead">
    <h2>My Progress</h2>
    <button class="close" id="closeProgress" type="button">✕</button>
  </div>

  <div class="tabs">
    <button class="tab active" id="tabJoined" type="button">Joined Challenges</button>
    <button class="tab" id="tabBadges" type="button">Badges Collected</button>
  </div>

  <!-- Joined Challenges -->
  <div class="list" id="joinedList">
    <?php if (empty($joinedChallenges)): ?>
      <div class="item muted">
        <div>
          <div class="name">No joined challenges yet</div>
          <div class="meta2">Join your first challenge to start earning badges.</div>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($joinedChallenges as $jc): ?>
        <?php
          $pts2 = (int)($jc["points_reward"] ?? 0);
          $range2 = ($jc["start_date"] ?? "") . " → " . ($jc["end_date"] ?? "");
        ?>
        <div class="item">
          <div>
            <div class="name"><?= htmlspecialchars($jc["challenge_name"]) ?></div>
            <div class="meta2"><?= (int)$pts2 ?> pts • <?= htmlspecialchars($range2) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Badges -->
  <div class="list" id="badgesList" style="display:none;">
    <?php if (empty($badges)): ?>
      <div class="item muted">
        <div>
          <div class="name">No badges collected yet</div>
          <div class="meta2">Badges will appear here once you earn them.</div>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($badges as $b): ?>
        <?php
          $icon = $b["icon_image"] ?? "";
          $icon = str_replace("..", "/RWDD_assignment", $icon);
          $bname = $b["badge_name"] ?? "Badge";
          $bdesc = $b["description"] ?? "";
          $date  = $b["date_awarded"] ?? "";
        ?>
        <div class="item">
          <?php if (!empty($b["icon_image"])): ?>
            <img class="badgeIcon" src="<?= htmlspecialchars($icon) ?>" alt="">
          <?php else: ?>
            <div class="badgeIcon"></div>
          <?php endif; ?>

          <div>
            <div class="name">🏅 <?= htmlspecialchars($bname) ?></div>
            <div class="meta2"><?= htmlspecialchars($bdesc) ?></div>
            <div class="meta2" style="margin-top:6px;">📅 Awarded: <?= htmlspecialchars($date) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<script>
  const overlay = document.getElementById('overlay');
  const panel = document.getElementById('panel');
  const openBtn = document.getElementById('openProgress');
  const closeBtn = document.getElementById('closeProgress');

  const tabJoined = document.getElementById('tabJoined');
  const tabBadges = document.getElementById('tabBadges');
  const joinedList = document.getElementById('joinedList');
  const badgesList = document.getElementById('badgesList');

  function openPanel(){
    overlay.classList.add('show');
    panel.classList.add('show');
  }
  function closePanel(){
    overlay.classList.remove('show');
    panel.classList.remove('show');
  }

  openBtn.addEventListener('click', openPanel);
  closeBtn.addEventListener('click', closePanel);
  overlay.addEventListener('click', closePanel);

  tabJoined.addEventListener('click', () => {
    tabJoined.classList.add('active');
    tabBadges.classList.remove('active');
    joinedList.style.display = 'block';
    badgesList.style.display = 'none';
  });

  tabBadges.addEventListener('click', () => {
    tabBadges.classList.add('active');
    tabJoined.classList.remove('active');
    joinedList.style.display = 'none';
    badgesList.style.display = 'block';
  });
</script>

</body>
</html>
