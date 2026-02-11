<?php
require_once 'admin_guard.php';
require_once __DIR__ . '/../Common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $challenge_name = trim($_POST['challenge_name']);
    $description    = trim($_POST['description']);
    $start_date     = $_POST['start_date'];
    $end_date       = $_POST['end_date'];
    $points_reward  = (int) $_POST['points_reward'];

    $image_name = null;

    if (!empty($_FILES['image']['name'])) {

        $target_dir = __DIR__ . '/../public/images/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO challenge 
        (created_by, challenge_name, description, start_date, end_date, points_reward, image)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $challenge_name,
        $description,
        $start_date,
        $end_date,
        $points_reward,
        $image_name
    ]);

    header("Location: challenge.php");
    exit;
}

$stmt = $pdo->query(
    "SELECT c.*, u.username 
     FROM challenge c
     LEFT JOIN user u ON c.created_by = u.id
     ORDER BY c.start_date DESC"
);
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Challenges</title>

<link href="../common/sidebar.css" rel="stylesheet">

<style>
body {
    background: #F3EAD7;
    font-family: Arial, sans-serif;
    margin: 0;
    color: #1E2D24;
}

.main {
    margin-left: 280px;
    padding: 40px;
}

h2 {
    margin-bottom: 6px;
}

p {
    margin-bottom: 20px;
}

form {
    background: #165540;
    padding: 24px;
    border-radius: 14px;
    color: white;
    margin-bottom: 35px;
}

form label {
    font-weight: bold;
}

form input,
form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    margin-bottom: 14px;
    border-radius: 6px;
    border: none;
}

button {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    border: none;
    padding: 10px 22px;
    border-radius: 22px;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #E6EFE9;
}

th {
    background: #165540;
    color: white;
    padding: 14px;
    text-align: left;
}

td {
    padding: 14px;
    border-bottom: 1px solid #ffffff;
    color: #1E2D24;
}

tbody tr {
    background: #A3CFAE;
}

tbody tr:nth-child(even) {
    background: #B7DCC0;
}

img {
    width: 90px;
    border-radius: 8px;
    object-fit: cover;
}
</style>
</head>

<body>

<?php include '../common/sidebar.php'; ?>

<div class="main">
    <h2>Manage Challenges</h2>
    <p>Create and manage Zero Waste challenges.</p>

    <form method="post" enctype="multipart/form-data">
        <label>Challenge Name</label>
        <input type="text" name="challenge_name" required>

        <label>Description</label>
        <textarea name="description" rows="3" required></textarea>

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <label>Points Reward</label>
        <input type="number" name="points_reward" required>

        <label>Challenge Image (PNG/JPG)</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Challenge</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Challenge</th>
                <th>Description</th>
                <th>Duration</th>
                <th>Points</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($challenges)): ?>
            <tr>
                <td colspan="6">No challenges created yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($challenges as $c): ?>
            <tr>
                <td>
                    <?php if ($c['image']): ?>
                        <img src="../public/images/<?= htmlspecialchars($c['image']) ?>">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($c['challenge_name']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td><?= $c['start_date'] ?> → <?= $c['end_date'] ?></td>
                <td><?= $c['points_reward'] ?></td>
                <td><?= htmlspecialchars($c['username']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
