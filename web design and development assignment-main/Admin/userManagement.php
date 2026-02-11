<?php
require_once 'admin_guard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['user_id'], $_POST['action'])) {
        header("Location: userManagement.php");
        exit;
    }

    $userId = (int) $_POST['user_id'];
    $action = $_POST['action'];

    if ($userId > 0) {
        if ($action === 'approve') {
            $stmt = $pdo->prepare(
                "UPDATE user SET status = 'active' WHERE id = ? AND role != 'admin'"
            );
            $stmt->execute([$userId]);
        }

        if ($action === 'suspend') {
            $stmt = $pdo->prepare(
                "UPDATE user SET status = 'suspended' WHERE id = ? AND role != 'admin'"
            );
            $stmt->execute([$userId]);
        }
    }

    header("Location: userManagement.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id, username, role, status
     FROM user
     WHERE role != 'admin'
     ORDER BY id ASC"
);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management</title>

<link href="../Common/sidebar.css" rel="stylesheet">

<style>
body {
    background: #F3EAD7;
    color: #000;
    font-family: Arial, sans-serif;
    margin: 0;
}

.main {
    margin-left: 280px;
    padding: 40px;
    box-sizing: border-box;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #A3CFAE;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #000;
    vertical-align: middle;
}

th {
    background: #165540;
    color: #000;
    text-align: left;
}

th:last-child,
td:last-child {
    text-align: center;
}

td:last-child form {
    display: flex;
    justify-content: center;
}

.active {
    font-weight: bold;
}

.suspended {
    color: #ff6b6b;
    font-weight: bold;
}

button {
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    min-width: 90px;
}

.suspend {
    background: #ff6b6b;
    color: white;
}

.approve {
    background: #165540;
    color: white;
}

.user-card {
    display: none;
}

@media (max-width: 768px) {

    .main {
        margin-left: 0;
        padding: 16px;
    }

    table {
        display: none;
    }

    .user-card {
        display: block;
        background: #A3CFAE;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 16px;
    }

    .user-card p {
        margin: 6px 0;
        font-size: 14px;
    }

    .user-card strong {
        color: #165540;
    }

    .user-card form {
        margin-top: 10px;
        text-align: center;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">
    <h2>User Management</h2>
    <p>Approve or suspend users.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td class="<?= $u['status'] ?>"><?= $u['status'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <button
                            class="<?= $u['status'] === 'active' ? 'suspend' : 'approve' ?>"
                            name="action"
                            value="<?= $u['status'] === 'active' ? 'suspend' : 'approve' ?>">
                            <?= $u['status'] === 'active' ? 'Suspend' : 'Approve' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php foreach ($users as $u): ?>
        <div class="user-card">
            <p><strong>ID:</strong> <?= $u['id'] ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($u['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($u['role']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($u['status']) ?></p>

            <form method="post">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <button
                    class="<?= $u['status'] === 'active' ? 'suspend' : 'approve' ?>"
                    name="action"
                    value="<?= $u['status'] === 'active' ? 'suspend' : 'approve' ?>">
                    <?= $u['status'] === 'active' ? 'Suspend' : 'Approve' ?>
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
