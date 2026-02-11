<?php
session_start();
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}

// get user id
$stmt = $pdo->prepare("SELECT id FROM user WHERE username = ?");
$stmt->execute([$username]);
$user_id = (int)$stmt->fetchColumn();

$success = "";
$error = "";

// send message
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($subject === "" || $message === "") {
        $error = "Please fill in Subject and Message.";
    } else {
        $ins = $pdo->prepare("INSERT INTO contact_admin (user_id, subject, message) VALUES (?, ?, ?)");
        $ins->execute([$user_id, $subject, $message]);
        $success = "Message sent to Admin successfully ✅";
    }
}

// fetch my messages
$list = $pdo->prepare("
    SELECT id, subject, message, admin_reply, status, created_at, replied_at
    FROM contact_admin
    WHERE user_id = ?
    ORDER BY id DESC
");
$list->execute([$user_id]);
$rows = $list->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Admin</title>

  <!-- ✅ keep ONLY sidebar.css (same as challenges.php) -->
  <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

  <style>
    :root{
      --cream:#F3EAD7;
      --deep:#165540;
      --panel:#1F5C44;
      --soft:#E6EFE9;
      --text:#0b3f2f;
      --shadow:0 14px 30px rgba(0,0,0,.12);
      --radius:18px;
    }

    /* ✅ IMPORTANT: don't do *{margin:0;padding:0} (it breaks sidebar spacing) */
    *{ box-sizing:border-box; }
    html, body{ margin:0; padding:0; }

    body{
      background:var(--cream);
      font-family:'Segoe UI', Arial, sans-serif;
      color:var(--text);
      overflow-x:hidden;
    }

    /* main layout like challenges */
    .main{
      margin-left:280px;
      padding:38px 40px 60px;
      min-height:100vh;
    }

    .page-top{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:16px;
      margin-bottom:18px;
      flex-wrap:wrap;
    }

    .page-title{
      margin:0;
      font-size:34px;
      font-weight:900;
      color:var(--text);
    }
    .page-sub{
      margin:6px 0 0;
      color:#5c7f72;
      font-size:13px;
    }

    .card{
      background:var(--panel);
      border-radius:var(--radius);
      padding:18px;
      box-shadow:var(--shadow);
      margin-bottom:18px;
    }
    .card h2{
      color:#fff;
      margin:0 0 12px;
      font-weight:900;
    }

    .input, textarea{
      width:100%;
      padding:12px;
      border-radius:12px;
      border:1.5px solid rgba(255,255,255,0.25);
      background:var(--soft);
      outline:none;
      font-size:14px;
    }
    textarea{ min-height:120px; resize:vertical; }

    .msg{
      margin:10px 0;
      padding:10px 12px;
      border-radius:12px;
      background:var(--soft);
    }

    .btn{
      border:0;
      cursor:pointer;
      background:linear-gradient(135deg, #165540, #2ECC71);
      color:#fff;
      font-weight:900;
      padding:12px 18px;
      border-radius:14px;
      box-shadow:0 10px 18px rgba(22,85,64,0.20);
      transition:.2s;
    }
    .btn:hover{ filter:brightness(1.05); transform:translateY(-1px); }

    .panel{
      background:var(--panel);
      border-radius:var(--radius);
      padding:18px;
      box-shadow:var(--shadow);
    }
    .panel-head{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:12px;
      gap:12px;
    }
    .panel-title{
      font-weight:900;
      color:#fff;
      font-size:16px;
    }

    .item{
      background:var(--soft);
      border-radius:14px;
      padding:14px;
      margin-top:12px;
      box-shadow:0 8px 16px rgba(0,0,0,0.10);
      border:1px solid rgba(22,85,64,0.18);
    }
    .item b{ color:var(--deep); }

    .badge{
      padding:6px 10px;
      border-radius:999px;
      font-weight:900;
      font-size:12px;
      display:inline-block;
    }
    .pending{ background:#ffe9a8; color:#6b5200; }
    .replied{ background:#c9f5d8; color:#0c5b2a; }

    .reply{
      margin-top:10px;
      background:#fff;
      padding:10px 12px;
      border-radius:12px;
      border-left:5px solid var(--deep);
    }

    /* ✅ responsive: same idea as challenges.php */
    @media (max-width:900px){
      .main{
        margin-left:0;
        padding:20px 16px;
      }
    }
  </style>
</head>

<body>

<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">

  <div class="page-top">
    <div>
      <h1 class="page-title">Contact Admin</h1>
      <p class="page-sub">Send messages to admin and track reply status.</p>
    </div>
  </div>

  <div class="card">
    <h2>Send a Message</h2>

    <?php if ($error): ?>
      <div class="msg" style="border-left:5px solid #ff4b4b;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="msg" style="border-left:5px solid #2ECC71;">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <input class="input" type="text" name="subject"
             placeholder="Subject (e.g., Recipe issue / Account issue)">
      <br><br>
      <textarea name="message" placeholder="Write your message..." class="input"></textarea>
      <br><br>
      <button class="btn" type="submit">Send Message</button>
    </form>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div class="panel-title">My Messages</div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="msg">No messages yet.</div>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <div class="item">
          <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
              <b><?= htmlspecialchars($r["subject"]) ?></b><br>
              <small><?= htmlspecialchars($r["created_at"]) ?></small>
            </div>

            <?php $isReplied = ($r["status"] === "replied"); ?>
            <span class="badge <?= $isReplied ? "replied" : "pending"; ?>">
              <?= strtoupper(htmlspecialchars($r["status"])) ?>
            </span>
          </div>

          <p><?= nl2br(htmlspecialchars($r["message"])) ?></p>

          <?php if (!empty($r["admin_reply"])): ?>
            <div class="reply">
              <b>Admin Reply:</b><br>
              <?= nl2br(htmlspecialchars($r["admin_reply"])) ?><br>
              <small>Replied at: <?= htmlspecialchars($r["replied_at"]) ?></small>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>

</div>

</body>
</html>
