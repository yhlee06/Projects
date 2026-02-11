<?php
require_once 'admin_guard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $stmt = $pdo->prepare(
        "UPDATE feedback 
         SET admin_reply = ?, status = 'replied', replied_at = NOW()
         WHERE id = ?"
    );
    $stmt->execute([$_POST['admin_reply'], $_POST['feedback_id']]);
    header("Location: feedback.php");
    exit;
}

$stmt = $pdo->query(
    "SELECT f.*, u.username
     FROM feedback f
     LEFT JOIN user u ON f.user_id = u.id
     ORDER BY status ASC, created_at DESC"
);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback</title>

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

.feedback-card {
    background: #1F5C44;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 18px;
}

.pending {
    border-left: 5px solid #010000ff;
}

.done {
    border-left: 5px solid #00cc99;
    opacity: 0.85;
}

textarea {
    width: 100%;
    border-radius: 8px;
    border: none;
    padding: 10px;
    margin-top: 10px;
    resize: vertical;
}

button {
    margin-top: 10px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    background: #00cc99;
    font-weight: bold;
}

small {
    color: #aaa;
}

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">
    <h2>Feedback</h2>
    <p>Review and respond to user feedback.</p>

    <?php if (count($feedbacks) === 0): ?>
        <div class="feedback-card">No feedback available.</div>
    <?php endif; ?>

    <?php foreach ($feedbacks as $f): ?>
        <div class="feedback-card <?= $f['status'] === 'pending' ? 'pending' : 'done' ?>">
            <strong><?= htmlspecialchars($f['username'] ?? 'User') ?></strong><br>
            <small><?= htmlspecialchars($f['created_at']) ?></small>

            <p style="margin-top:10px;">
                <?= nl2br(htmlspecialchars($f['message'])) ?>
            </p>

            <?php if ($f['status'] === 'pending'): ?>
                <form method="post">
                    <input type="hidden" name="feedback_id" value="<?= $f['id'] ?>">
                    <textarea name="admin_reply" required placeholder="Type admin reply..."></textarea>
                    <button type="submit" name="reply">Mark as Replied</button>
                </form>
            <?php else: ?>
                <p><strong>Admin Reply:</strong><br>
                    <?= nl2br(htmlspecialchars($f['admin_reply'])) ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
