<?php
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$myId = (int)($user['id'] ?? 0);

/* =========================
   A) SEND FEEDBACK (student)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_feedback'])) {
    $message = trim($_POST['message'] ?? '');

    if ($message !== '') {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, message, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$myId, $message]);
    }
    header("Location: feedback.php");
    exit;
}

/* =========================
   B) ADD COMMENT (opinion)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $feedbackId = (int)($_POST['feedback_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($feedbackId > 0 && $comment !== '') {
        // ensure feedback exists
        $chk = $pdo->prepare("SELECT id FROM feedback WHERE id = ?");
        $chk->execute([$feedbackId]);
        if ($chk->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO feedback_comments (feedback_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$feedbackId, $myId, $comment]);
        }
    }

    header("Location: feedback.php");
    exit;
}

/* =========================
   LOAD ALL FEEDBACK (everyone)
========================= */
$feedbackList = $pdo->query("
    SELECT f.id, f.user_id, f.message, f.admin_reply, f.status, f.created_at,
           u.username, u.name, u.user_type, u.role
    FROM feedback f
    JOIN user u ON u.id = f.user_id
    ORDER BY f.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   LOAD COMMENTS for all feedback
========================= */
$commentsByFeedback = [];
if (!empty($feedbackList)) {
    $ids = array_column($feedbackList, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT c.id, c.feedback_id, c.user_id, c.comment, c.created_at,
               u.username, u.name, u.user_type, u.role
        FROM feedback_comments c
        JOIN user u ON u.id = c.user_id
        WHERE c.feedback_id IN ($placeholders)
        ORDER BY c.created_at ASC
    ");
    $stmt->execute($ids);
    $allComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($allComments as $c) {
        $fid = (int)$c['feedback_id'];
        if (!isset($commentsByFeedback[$fid])) $commentsByFeedback[$fid] = [];
        $commentsByFeedback[$fid][] = $c;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Feedback</title>

<link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

<style>
  body{
    background:#F3EAD7;
    color:#1E2D24;
    font-family: Arial, sans-serif;
    margin:0;
  }

  .main{
    margin-left: 260px;
    padding: 40px 54px;
    min-height: 100vh;
    box-sizing: border-box;
  }

  @media (max-width: 900px){
    .main{ 
      margin-left:0; 
      padding: 25px; 
      margin-top:15px;
  }
  }

  .container{
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
  }

  .title{
    color:#165540;
    margin:0 0 6px;
    font-size:40px;
    font-weight:900;
  }
  .sub{
    margin:0 0 18px;
    color:#355b49;
    font-size:13px;
  }

  /* big cards */
  .card{
    background:#E6EFE9;
    border-radius:18px;
    padding:18px;
    box-shadow:0 6px 14px rgba(22,85,64,0.12);
    border:1px solid rgba(22,85,64,0.10);
    margin-bottom:16px;
  }

  textarea{
    width:100%;
    min-height:110px;
    resize:vertical;
    padding:14px;
    border-radius:12px;
    border:1.5px solid #88B393;
    outline:none;
    background:#fff;
    box-sizing:border-box;
  }

  .btn{
    display:inline-block;
    margin-top:12px;
    padding:10px 16px;
    border-radius:10px;
    border:none;
    cursor:pointer;
    background:#165540;
    color:#fff;
    font-weight:800;
  }
  .btn:hover{ background:#1e7a5a; }

  /* feedback item */
  .fb{
    background:#1F5C44;
    border-radius:18px;
    padding:16px;
    color:#fff;
    margin-bottom:14px;
    border:1px solid rgba(0,0,0,0.10);
  }

  .fb-top{
    display:flex;
    justify-content:space-between;
    gap:12px;
    align-items:flex-start;
    margin-bottom:10px;
  }

  .fb-name{
    font-weight:900;
    font-size:16px;
  }
  .fb-meta{
    font-size:12px;
    opacity:.85;
    margin-top:4px;
  }

  .badge{
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
  }
  .pending{ background:#F6D365; color:#1E2D24; }
  .replied{ background:#2ECC71; color:#0b2a1d; }
  .declined{ background:#ff5b5b; color:#fff; }
  .approved{ background:#2ECC71; color:#0b2a1d; }

  .fb-msg{
    background: rgba(230,239,233,0.12);
    border:1px solid rgba(255,255,255,0.18);
    border-radius:14px;
    padding:12px;
    margin-top:10px;
    line-height:1.5;
  }

  .admin-reply{
    margin-top:10px;
    background:#ffffff;
    color:#1E2D24;
    border-radius:14px;
    padding:12px;
    border:1.5px solid #88B393;
  }
  .admin-reply b{ color:#165540; }

  /* comments */
  .comments{
    margin-top:12px;
    background: rgba(0,0,0,0.08);
    border:1px solid rgba(255,255,255,0.15);
    border-radius:14px;
    padding:12px;
  }

  .comment{
    padding:10px 10px;
    border-radius:12px;
    background: rgba(255,255,255,0.10);
    border:1px solid rgba(255,255,255,0.14);
    margin-bottom:10px;
  }
  .comment:last-child{ margin-bottom:0; }

  .comment-top{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-bottom:6px;
    font-size:12px;
    opacity:.9;
  }

  .comment-text{
    font-size:13px;
    line-height:1.4;
  }

  .comment-form{
    margin-top:10px;
    display:flex;
    gap:10px;
  }

  .comment-form input{
    flex:1;
    padding:12px 12px;
    border-radius:12px;
    border:1.5px solid #88B393;
    outline:none;
    background:#E6EFE9;
  }

  .btn-small{
    padding:10px 14px;
    border-radius:12px;
    border:none;
    cursor:pointer;
    font-weight:900;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color:#fff;
  }
  .btn-small:hover{
    background: linear-gradient(135deg, #1e7a5a, #3ee38f);
  }
</style>
</head>

<body>
<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
  <div class="container">
    <h1 class="title">Feedback</h1>
    <p class="sub">Send feedback to admin, and also view & comment on feedback from everyone.</p>

    <!-- Send feedback -->
    <div class="card">
      <form method="POST">
        <textarea name="message" placeholder="Write your feedback here..." required></textarea>
        <button class="btn" type="submit" name="send_feedback">Send Feedback</button>
      </form>
    </div>

    <!-- All feedback -->
    <?php if (empty($feedbackList)): ?>
      <div class="card">No feedback yet.</div>
    <?php else: ?>
      <?php foreach ($feedbackList as $f): ?>
        <?php
          $status = strtolower($f['status'] ?? 'pending');
          $badgeClass = 'pending';
          if ($status === 'replied') $badgeClass = 'replied';
          else if ($status === 'declined') $badgeClass = 'declined';
          else if ($status === 'approved') $badgeClass = 'approved';
        ?>
        <div class="fb">
          <div class="fb-top">
            <div>
              <div class="fb-name">
                <?= htmlspecialchars($f['name'] ?: $f['username']) ?>
                <span style="font-weight:500;opacity:.75;">@<?= htmlspecialchars($f['username']) ?></span>
              </div>
              <div class="fb-meta">
                <?= htmlspecialchars($f['role'] ?? '') ?> <?= !empty($f['user_type']) ? " | " . htmlspecialchars($f['user_type']) : "" ?>
                &nbsp;•&nbsp;
                <?= htmlspecialchars($f['created_at']) ?>
              </div>
            </div>
            <div class="badge <?= $badgeClass ?>">
              <?= htmlspecialchars(ucfirst($status)) ?>
            </div>
          </div>

          <div class="fb-msg">
            <?= nl2br(htmlspecialchars($f['message'])) ?>
          </div>

          <?php if (!empty($f['admin_reply'])): ?>
            <div class="admin-reply">
              <b>Admin Reply</b><br>
              <?= nl2br(htmlspecialchars($f['admin_reply'])) ?>
            </div>
          <?php endif; ?>

          <!-- Comments -->
          <div class="comments">
            <div style="font-weight:900;margin-bottom:10px;">Comments</div>

            <?php $fid = (int)$f['id']; ?>
            <?php if (empty($commentsByFeedback[$fid])): ?>
              <div style="font-size:13px;opacity:.85;">No comments yet. Be the first to reply 😊</div>
            <?php else: ?>
              <?php foreach ($commentsByFeedback[$fid] as $c): ?>
                <div class="comment">
                  <div class="comment-top">
                    <div>
                      <b><?= htmlspecialchars($c['name'] ?: $c['username']) ?></b>
                      <span style="opacity:.75;">@<?= htmlspecialchars($c['username']) ?></span>
                      <span style="opacity:.75;">
                        <?= !empty($c['user_type']) ? " | " . htmlspecialchars($c['user_type']) : "" ?>
                      </span>
                    </div>
                    <div><?= htmlspecialchars($c['created_at']) ?></div>
                  </div>
                  <div class="comment-text"><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>

            <!-- Add comment -->
            <form class="comment-form" method="POST">
              <input type="hidden" name="feedback_id" value="<?= (int)$f['id'] ?>">
              <input type="text" name="comment" placeholder="Write your opinion..." required autocomplete="off">
              <button class="btn-small" type="submit" name="add_comment">Reply</button>
            </form>

          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
