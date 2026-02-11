<?php
require_once 'admin_guard.php';

$status = $_GET['status'] ?? 'pending';

$allowedStatus = ['pending', 'approved', 'declined', 'all'];
if (!in_array($status, $allowedStatus)) {
    $status = 'pending';
}

if ($status === 'all') {
    $stmt = $pdo->prepare("
        SELECT id, title, image, status
        FROM recipe
        ORDER BY id DESC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT id, title, image, status
        FROM recipe
        WHERE status = ?
        ORDER BY id DESC
    ");
    $stmt->execute([$status]);
}

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recipe Review</title>

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

.filter-bar {
    display: flex;
    gap: 12px;
    margin: 20px 0;
}

.filter-bar a {
    padding: 8px 16px;
    border-radius: 20px;
    background: #1F5C44;
    color: #bbb;
    text-decoration: none;
    font-weight: bold;
}

.filter-bar a.active {
    background: #00cc99;
    color: black;
}

.recipe-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.recipe-card {
    background: #1F5C44;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s;
}

.recipe-card:hover {
    transform: scale(1.02);
}

.recipe-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.recipe-card h3 {
    margin: 0;
    padding: 12px;
    text-align: center;
}

.status {
    text-align: center;
    padding-bottom: 10px;
    font-weight: bold;
}

.status.pending { color: #f1c40f; }
.status.approved { color: #2ecc71; }
.status.declined { color: #e74c3c; }

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px;
    }

    .recipe-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">
    <h2>Recipe Review</h2>
    <p>Click a recipe to review details.</p>

    <div class="filter-bar">
        <a href="recipeReview.php?status=pending" class="<?= $status==='pending'?'active':'' ?>">Pending</a>
        <a href="recipeReview.php?status=approved" class="<?= $status==='approved'?'active':'' ?>">Approved</a>
        <a href="recipeReview.php?status=declined" class="<?= $status==='declined'?'active':'' ?>">Declined</a>
        <a href="recipeReview.php?status=all" class="<?= $status==='all'?'active':'' ?>">All</a>
    </div>

    <div class="recipe-grid">
        <?php if (count($recipes) === 0): ?>
            <p>No recipes found.</p>
        <?php else: ?>
            <?php foreach ($recipes as $r): ?>
                <a href="recipeDetail.php?id=<?= $r['id'] ?>" style="text-decoration:none; color:white;">
                    <div class="recipe-card">
                        <img src="../public/recipes/<?= htmlspecialchars($r['image']) ?>"
                             alt="<?= htmlspecialchars($r['title']) ?>">
                        <h3><?= htmlspecialchars($r['title']) ?></h3>
                        <div class="status <?= $r['status'] ?>">
                            <?= ucfirst($r['status']) ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
