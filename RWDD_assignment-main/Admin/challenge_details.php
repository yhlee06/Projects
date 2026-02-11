<?php
require_once 'admin_guard.php';
require_once __DIR__ . '/../Common/db.php';

if (!isset($_GET['id'])) {
    header("Location: challenge.php");
    exit;
}

$challenge_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT c.*, u.username AS creator
    FROM challenge c
    LEFT JOIN user u ON c.created_by = u.id
    WHERE c.id = ?
");
$stmt->execute([$challenge_id]);
$challenge = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$challenge) {
    die("Challenge not found.");
}

$stmt = $pdo->prepare("
    SELECT uc.*, u.username
    FROM user_challenge uc
    JOIN user u ON uc.user_id = u.id
    WHERE uc.challenge_id = ?
    ORDER BY uc.joined_at ASC
");
$stmt->execute([$challenge_id]);
$joinedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = new DateTime();
$start = new DateTime($challenge['start_date']);
$end   = new DateTime($challenge['end_date']);

if ($today < $start) {
    $status = "Upcoming";
    $daysLeft = $today->diff($start)->days . " days to start";
} elseif ($today > $end) {
    $status = "Ended";
    $daysLeft = "0 days";
} else {
    $status = "Ongoing";
    $daysLeft = $today->diff($end)->days . " days left";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Challenge Details</title>
<link href="../Common/sidebar.css" rel="stylesheet">

<style>
body {
    background:#F3EAD7;
    font-family:Arial;
    margin:0;
    color:#1E2D24;
}

.main {
    margin-left:280px;
    padding:40px;
}

.card {
    background:#E6EFE9;
    padding:24px;
    border-radius:14px;
    margin-bottom:30px;
}

h2 {
    margin-top:0;
}

table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#165540;
    color:#fff;
    padding:12px;
    text-align:left;
}

td {
    padding:12px;
    background:#B7DCC0;
}

tr:nth-child(even) td {
    background:#A3CFAE;
}

.badge {
    padding:6px 12px;
    border-radius:12px;
    font-weight:bold;
}

.upcoming { background:#f1c40f; }
.ongoing { background:#2ecc71; }
.ended { background:#e74c3c; color:#fff; }

@media (max-width: 768px) {

    .main {
        margin-left: 0 !important;
        padding: 15px;
    }

    .card {
        padding: 16px;
    }

    table {
        font-size: 13px;
    }

    th, td {
        padding: 8px;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">

    <div class="card">
        <h2><?= htmlspecialchars($challenge['challenge_name']) ?></h2>
        <p><?= htmlspecialchars($challenge['description']) ?></p>

        <p>
            <strong>Duration:</strong>
            <?= $challenge['start_date'] ?> → <?= $challenge['end_date'] ?>
        </p>

        <p>
            <strong>Status:</strong>
            <span class="badge <?= strtolower($status) ?>">
                <?= $status ?>
            </span>
        </p>

        <p><strong><?= $daysLeft ?></strong></p>
        <p><strong>Points:</strong> <?= $challenge['points_reward'] ?></p>
        <p><strong>Created by:</strong> <?= htmlspecialchars($challenge['creator']) ?></p>
    </div>

    <div class="card">
        <h3>Joined Users</h3>

        <?php if (empty($joinedUsers)): ?>
            <p>No users have joined this challenge yet.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Joined At</th>
                </tr>
                <?php foreach ($joinedUsers as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= $u['joined_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
