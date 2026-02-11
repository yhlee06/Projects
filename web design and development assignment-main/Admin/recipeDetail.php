<?php
require_once 'admin_guard.php';

if (!isset($_GET['id'])) {
    header("Location: recipeReview.php");
    exit;
}

$recipeId = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['approve'])) {
        $stmt = $pdo->prepare(
            "UPDATE recipe 
             SET status = 'approved',
                 date_posted = CURDATE()
             WHERE id = ?"
        );
        $stmt->execute([$recipeId]);
        header("Location: recipeReview.php");
        exit;
    }

    if (isset($_POST['decline'])) {
        $stmt = $pdo->prepare(
            "UPDATE recipe 
             SET status = 'declined'
             WHERE id = ?"
        );
        $stmt->execute([$recipeId]);
        header("Location: recipeReview.php");
        exit;
    }
}

$stmt = $pdo->prepare(
    "SELECT r.*, u.username AS created_by_name
     FROM recipe r
     LEFT JOIN user u ON r.user_id = u.id
     WHERE r.id = ?"
);
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    header("Location: recipeReview.php");
    exit;
}

$prepTime  = $recipe['prep_time'];
$cookTime  = $recipe['cook_time'];
$totalTime = 'Not specified';

if (!empty($prepTime) && !empty($cookTime)) {
    preg_match('/\d+/', $prepTime, $prepMatch);
    preg_match('/\d+/', $cookTime, $cookMatch);

    if ($prepMatch && $cookMatch) {
        $totalTime = ((int)$prepMatch[0] + (int)$cookMatch[0]) . ' minutes';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($recipe['title']) ?></title>

<link href="../Common/sidebar.css" rel="stylesheet">

<style>
body {
    background: #F3EAD7;
    color: white;
    font-family: Arial, sans-serif;
    margin: 0;
    overflow-x: hidden;
}

.main {
    margin-left: 280px;
    padding: 40px;
}

.recipe-container {
    background: #475e48ff;
    border-radius: 14px;
    padding: 20px;
    max-width: 900px;
}

.recipe-container img {
    width: 100%;
    max-height: 380px;
    object-fit: contain;
    background: #475e48ff;
    border-radius: 12px;
    margin-bottom: 20px;
}

.meta, .stats {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.meta-box {
    background: #1f1f1f;
    padding: 12px 16px;
    border-radius: 8px;
}

.section {
    margin-bottom: 25px;
    line-height: 1.6;
    color: #ddd;
}

.actions {
    display: flex;
    gap: 16px;
}

button {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

.approve { background: #00cc99; }
.decline { background: #ff6b6b; }

.back {
    color: #aaa;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}

@media (max-width: 768px) {

    .main {
        margin-left: 0;
        padding: 16px;
    }

    .recipe-container {
        max-width: 100%;
        padding: 16px;
    }

    .recipe-container img {
        max-height: 260px;
    }

    .meta,
    .stats {
        flex-direction: column;
        gap: 10px;
    }

    .meta-box {
        width: 100%;
    }

    .actions {
        flex-direction: column;
    }

    .actions button {
        width: 100%;
    }
}
</style>
</head>

<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">

    <a href="recipeReview.php" class="back">← Back to Recipe Review</a>

    <div class="recipe-container">
        <h2><?= htmlspecialchars($recipe['title']) ?></h2>

        <img src="../public/recipes/<?= htmlspecialchars($recipe['image']) ?>">

        <div class="meta">
            <div class="meta-box"><strong>Prep Time:</strong><br><?= htmlspecialchars($prepTime ?? '—') ?></div>
            <div class="meta-box"><strong>Cook Time:</strong><br><?= htmlspecialchars($cookTime ?? '—') ?></div>
            <div class="meta-box"><strong>Total Time:</strong><br><?= htmlspecialchars($totalTime) ?></div>
            <div class="meta-box"><strong>Status:</strong><br><?= htmlspecialchars($recipe['status']) ?></div>
        </div>

        <?php if ($recipe['status'] === 'approved'): ?>
        <div class="stats">
            <div class="meta-box"> Views: <?= (int)$recipe['views'] ?></div>
            <div class="meta-box"> Likes: <?= (int)$recipe['like_number'] ?></div>
            <div class="meta-box"> Saves: <?= (int)$recipe['saves_number'] ?></div>
            <div class="meta-box"> Posted: <?= htmlspecialchars($recipe['date_posted']) ?></div>
            <div class="meta-box"> Created by: <?= htmlspecialchars($recipe['created_by_name']) ?></div>
        </div>
        <?php endif; ?>

        <div class="section">
            <strong>Description</strong><br>
            <?= nl2br(htmlspecialchars($recipe['description'])) ?>
        </div>

        <div class="section">
            <strong>Ingredients</strong><br>
            <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?>
        </div>

        <div class="section">
            <strong>Instructions</strong><br>
            <?= nl2br(htmlspecialchars($recipe['instructions'])) ?>
        </div>

        <?php if ($recipe['status'] === 'pending'): ?>
            <div class="actions">
                <form method="post">
                    <button type="submit" name="approve" class="approve">Approve</button>
                </form>
                <form method="post">
                    <button type="submit" name="decline" class="decline">Decline</button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
