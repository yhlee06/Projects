<?php
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$myId = (int)($user['id'] ?? 0);

/* =========================================================
   A) COMMUNITY FEED ACTIONS (create post + like)
========================================================= */

// Create post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $content = trim($_POST['content'] ?? '');
    if ($content !== '') {
        $stmt = $pdo->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)");
        $stmt->execute([$myId, $content]);
    }
    header("Location: community.php");
    exit;
}

// Like / Unlike post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_like'])) {
    $postId = (int)($_POST['post_id'] ?? 0);

    if ($postId > 0) {
        $stmt = $pdo->prepare("SELECT id FROM community_likes WHERE user_id=? AND post_id=?");
        $stmt->execute([$myId, $postId]);
        $liked = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($liked) {
            $pdo->prepare("DELETE FROM community_likes WHERE user_id=? AND post_id=?")->execute([$myId, $postId]);
        } else {
            $pdo->prepare("INSERT INTO community_likes (user_id, post_id) VALUES (?, ?)")->execute([$myId, $postId]);
        }
    }

    header("Location: community.php");
    exit;
}

/* =========================================================
   B) MESSAGES ACTIONS (select user + send message)
========================================================= */

$chatWith = isset($_GET['u']) ? (int)$_GET['u'] : 0;

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_msg'])) {
    $receiverId = (int)($_POST['receiver_id'] ?? 0);
    $msg = trim($_POST['message'] ?? '');

    if ($receiverId > 0 && $msg !== '') {
        $stmt = $pdo->prepare("INSERT INTO community_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$myId, $receiverId, $msg]);
    }

    header("Location: community.php?u=" . $receiverId . "&chat=1");
    exit;
}

/* =========================================================
   LOAD DATA (feed + users list + chat messages)
========================================================= */

// Feed posts with like count + whether I liked
$posts = $pdo->query("
    SELECT p.id, p.user_id, p.content, p.created_at,
           u.username, u.name,
           (SELECT COUNT(*) FROM community_likes cl WHERE cl.post_id = p.id) AS like_count,
           EXISTS(SELECT 1 FROM community_likes cl2 WHERE cl2.post_id = p.id AND cl2.user_id = {$myId}) AS i_liked
    FROM community_posts p
    JOIN user u ON u.id = p.user_id
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ONLY Students (role=user + user_type=student)
$stmt = $pdo->prepare("
    SELECT id, username, name
    FROM user
    WHERE id <> ?
      AND role = 'user'
      AND user_type = 'student'
    ORDER BY username ASC
");
$stmt->execute([$myId]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Selected chat user
$chatUser = null;
if ($chatWith > 0) {
    $stmt = $pdo->prepare("
        SELECT id, username, name
        FROM user
        WHERE id = ?
          AND role = 'user'
          AND user_type = 'student'
        LIMIT 1
    ");
    $stmt->execute([$chatWith]);
    $chatUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$chatUser) $chatWith = 0;
}

// Messages between me and selected user
$messages = [];
if ($chatWith > 0) {
    $stmt = $pdo->prepare("
        SELECT sender_id, receiver_id, message, sent_at
        FROM community_messages
        WHERE (sender_id=? AND receiver_id=?)
           OR (sender_id=? AND receiver_id=?)
        ORDER BY sent_at ASC
    ");
    $stmt->execute([$myId, $chatWith, $chatWith, $myId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$openChat = (isset($_GET['chat']) && $_GET['chat'] === '1') || ($chatWith > 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Community</title>

<link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

<style>
  body{
    background:#F3EAD7;
    color:#1E2D24;
    font-family: Arial, sans-serif;
    margin:0;
  }

  /* make page align like your other pages */
  .main{
    margin-left:260px;
    padding:34px 54px;
    min-height:100vh;
    box-sizing:border-box;
  }

  .page-wrap{
    max-width:1100px;
    margin:0 auto; /* ✅ CENTER */
  }

  .title{
    color:#165540;
    margin:0 0 8px;
    font-size:44px;
    font-weight:900;
    letter-spacing:0.3px;
  }

  .sub{
    margin:0 0 18px;
    color:#1E2D24;
    font-size:14px;
  }

  /* ✅ ONE CENTER COLUMN (no right column) */
  .layout{
    display:block;
  }

  /* Feed big green card */
  .box{
    background:#1F5C44;
    border-radius:18px;
    overflow:hidden;
    box-shadow: 0 6px 14px rgba(22, 85, 64, 0.18);
    border:1px solid rgba(0,0,0,0.10);
  }

  /* Composer */
  .compose{
    padding:18px;
    border-bottom:1px solid rgba(255,255,255,0.15);
  }

  .compose textarea{
    width:100%;
    min-height:88px;
    resize:none;
    padding:12px 14px;
    border-radius:14px;
    border:1.5px solid #88B393;
    background:#E6EFE9;
    color:#1E2D24;
    outline:none;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    font-size:14px;
  }

  .compose textarea:focus{
    border-color:#165540;
    box-shadow: 0 0 0 2px rgba(22, 85, 64, 0.25);
  }

  .compose-row{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-top:10px;
    align-items:center;
  }

  .btn{
    padding:10px 16px;
    border-radius:999px;
    border:none;
    cursor:pointer;
    font-weight:900;
    transition: 0.2s;
    font-size:13px;
  }

  .btn-green{
    background: linear-gradient(135deg, #165540, #2ECC71);
    color:#fff;
  }
  .btn-green:hover{
    background: linear-gradient(135deg, #1e7a5a, #3ee38f);
    transform: translateY(-1px);
  }

  .btn-gray{
    background: rgba(230,239,233,0.22);
    color:#fff;
    border:1px solid rgba(255,255,255,0.20);
  }

  .feed{
    padding:16px 18px 18px;
    display:flex;
    flex-direction:column;
    gap:12px;
    max-height: calc(100vh - 260px);
    overflow:auto;
  }

  .post{
    background: rgba(230,239,233,0.12);
    border:1px solid rgba(255,255,255,0.20);
    border-radius:16px;
    padding:14px;
    color:#fff;
  }

  .post-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
  }

  .who{
    font-weight:900;
    font-size:14px;
    color:#fff;
  }

  .time{
    font-size:12px;
    color:rgba(255,255,255,0.80);
  }

  .content{
    font-size:14px;
    line-height:1.5;
    color:rgba(255,255,255,0.95);
  }

  .post-actions{
    margin-top:12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
  }

  .likes{
    font-size:12px;
    color:rgba(255,255,255,0.85);
  }

  /* ===========================
     ✅ CHAT FLOATING BUTTON
  ============================ */
  .chat-fab{
    position:fixed;
    right:26px;
    bottom:24px;
    width:62px;
    height:62px;
    border-radius:50%;
    border:none;
    cursor:pointer;
    background: linear-gradient(135deg, #165540, #2ECC71);
    box-shadow: 0 12px 24px rgba(0,0,0,0.18);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999;
  }
  .chat-fab:hover{ transform: translateY(-2px); }
  .chat-fab img{ width:40px; height:40px; }

  /* ===========================
     ✅ CHAT MODAL (POPUP)
  ============================ */
  .chat-overlay{
    position:fixed;
    inset:0;
    background: rgba(0,0,0,0.25);
    display:none;
    z-index:9998;
  }
  .chat-overlay.show{ display:block; }

  .chat-modal{
    position:fixed;
    right:22px;
    bottom:100px;
    width:380px;
    height:520px;
    background:#1F5C44;
    border-radius:18px;
    box-shadow: 0 14px 30px rgba(0,0,0,0.22);
    overflow:hidden;
    display:none;
    z-index:9999;
    border:1px solid rgba(255,255,255,0.18);
  }
  .chat-modal.show{ display:block; }

  .chat-head{
    padding:14px 14px;
    color:#fff;
    font-weight:900;
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:1px solid rgba(255,255,255,0.18);
  }
  .chat-close{
    border:none;
    background: rgba(255,255,255,0.14);
    color:#fff;
    width:34px;
    height:34px;
    border-radius:10px;
    cursor:pointer;
    font-weight:900;
  }

  .chat-wrap{
    display:grid;
    grid-template-columns: 150px 1fr;
    height: calc(520px - 60px);
  }

  .users{
    border-right:1px solid rgba(255,255,255,0.18);
    overflow:auto;
  }

  .user-item{
    display:block;
    padding:10px 12px;
    color:#fff;
    text-decoration:none;
    border-bottom:1px solid rgba(255,255,255,0.10);
  }

  .user-item:hover{ background: rgba(0,0,0,0.10); }

  .user-item.active{
    background: rgba(46, 204, 113, 0.22);
    border-left:4px solid #2ECC71;
  }

  .chat{
    display:flex;
    flex-direction:column;
    min-width:0;
  }

  .chat-body{
    padding:12px;
    overflow:auto;
    flex:1;
    background: rgba(0,0,0,0.06);
  }

  .bubble{
    max-width:80%;
    padding:10px 12px;
    border-radius:14px;
    margin-bottom:10px;
    font-size:13px;
    line-height:1.4;
    word-wrap:break-word;
  }

  .mine{
    margin-left:auto;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color:#fff;
    font-weight:800;
  }

  .theirs{
    background:#E6EFE9;
    border:1.5px solid #88B393;
    color:#1E2D24;
  }

  .chat-send{
    padding:10px;
    border-top:1px solid rgba(255,255,255,0.18);
    display:flex;
    gap:8px;
  }

  .chat-send input{
    flex:1;
    padding:10px 12px;
    border-radius:12px;
    border:1.5px solid #88B393;
    background:#E6EFE9;
    color:#1E2D24;
    outline:none;
  }

  .chat-send input:focus{
    border-color:#165540;
    box-shadow: 0 0 0 2px rgba(22, 85, 64, 0.25);
  }

  /* responsive */
  @media (max-width: 900px){
    .main{
      margin-left:0;
      padding:20px;
    }
    .chat-modal{
      right:12px;
      left:12px;
      width:auto;
    }
  }
</style>
</head>

<body>
<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
  <div class="page-wrap">
    <h1 class="title">Community</h1>
    <p class="sub">Post updates, like posts, and message users; all in one place.</p>

    <div class="layout">
      <!-- CENTER FEED -->
      <div class="box">
        <div class="compose">
          <form method="POST">
            <textarea name="content" placeholder="Share something with the community..." required></textarea>
            <div class="compose-row">
              <div style="color:rgba(255,255,255,0.65);font-size:14px;">
                 Tip: Keep it short & meaningful 
                   <img src="/RWDD_assignment/public/icons/plant.png" alt="Chat" width='30' height='30'>
              </div>
              <button class="btn btn-green" name="create_post" type="submit">Post</button>
            </div>
          </form>
        </div>

        <div class="feed">
          <?php if (empty($posts)): ?>
            <div style="color:rgba(255,255,255,0.75);font-size:13px;">No posts yet.</div>
          <?php else: ?>
            <?php foreach ($posts as $p): ?>
              <div class="post">
                <div class="post-top">
                  <div class="who">
                    <?= htmlspecialchars($p['name'] ?: $p['username']) ?>
                    <span style="font-weight:500;color:rgba(255,255,255,0.65);">@<?= htmlspecialchars($p['username']) ?></span>
                  </div>
                  <div class="time"><?= htmlspecialchars($p['created_at']) ?></div>
                </div>

                <div class="content"><?= nl2br(htmlspecialchars($p['content'])) ?></div>

                <div class="post-actions">
                  <form method="POST" style="margin:0;">
                    <input type="hidden" name="post_id" value="<?= (int)$p['id'] ?>">
                    <button class="btn <?= $p['i_liked'] ? 'btn-gray' : 'btn-green' ?>" name="toggle_like" type="submit">
                      <?= $p['i_liked'] ? 'Liked' : 'Like' ?>
                    </button>
                  </form>
                  <div class="likes"><?= (int)$p['like_count'] ?> likes</div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Floating Chat Button -->
<button class="chat-fab" id="chatFab" title="Chat">
  <img src="/RWDD_assignment/public/icons/feedback.png" alt="Chat">
</button>

<!-- Chat Popup -->
<div class="chat-overlay" id="chatOverlay"></div>

<div class="chat-modal" id="chatModal">
  <div class="chat-head">
    <div>
      <?= $chatUser ? "Chat: " . htmlspecialchars($chatUser['name'] ?: $chatUser['username']) : "Messages (Students)" ?>
    </div>
    <button class="chat-close" id="chatClose">✕</button>
  </div>

  <div class="chat-wrap">
    <!-- users list -->
    <div class="users">
      <?php foreach ($users as $u): 
          $uid = (int)$u['id'];
          $active = ($uid === $chatWith) ? 'active' : '';
      ?>
        <a class="user-item <?= $active ?>" href="community.php?u=<?= $uid ?>&chat=1">
          <?= htmlspecialchars($u['name'] ?: $u['username']) ?>
          <div style="font-size:11px;color:rgba(255,255,255,0.65);">@<?= htmlspecialchars($u['username']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- chat window -->
    <div class="chat">
      <div class="chat-body" id="chatBody">
        <?php if (!$chatUser): ?>
          <div style="color:rgba(255,255,255,0.75);font-size:13px;">Pick a student to start chatting.</div>
        <?php else: ?>
          <?php if (empty($messages)): ?>
            <div style="color:rgba(255,255,255,0.75);font-size:13px;">No messages yet. Say hi 👋</div>
          <?php endif; ?>

          <?php foreach ($messages as $m): 
              $isMine = ((int)$m['sender_id'] === $myId);
          ?>
            <div class="bubble <?= $isMine ? 'mine' : 'theirs' ?>">
              <?= nl2br(htmlspecialchars($m['message'])) ?>
              <div style="font-size:11px;opacity:0.7;margin-top:6px;"><?= htmlspecialchars($m['sent_at']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if ($chatUser): ?>
        <form class="chat-send" method="POST">
          <input type="hidden" name="receiver_id" value="<?= (int)$chatUser['id'] ?>">
          <input type="text" name="message" placeholder="Type message..." required autocomplete="off">
          <button class="btn btn-green" name="send_msg" type="submit">Send</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  const fab = document.getElementById("chatFab");
  const modal = document.getElementById("chatModal");
  const overlay = document.getElementById("chatOverlay");
  const closeBtn = document.getElementById("chatClose");

  function openChat(){
    modal.classList.add("show");
    overlay.classList.add("show");
    // scroll chat to bottom
    const cb = document.getElementById("chatBody");
    if (cb) cb.scrollTop = cb.scrollHeight;
  }
  function closeChat(){
    modal.classList.remove("show");
    overlay.classList.remove("show");
  }

  fab.addEventListener("click", openChat);
  closeBtn.addEventListener("click", closeChat);
  overlay.addEventListener("click", closeChat);

  // auto-open if url has chat=1 or u=...
  <?php if ($openChat): ?>
    openChat();
  <?php endif; ?>
</script>

</body>
</html>
