<?php
session_start();
// Use your central connection file
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

// 1. Handle Recipe Removal
if (isset($_GET['remove_id'])) {
    $remove_id = (int)$_GET['remove_id'];
    try {
        // Table name is singular 'recipe'
        $stmt = $pdo->prepare("DELETE FROM recipe WHERE id = ?");
        $stmt->execute([$remove_id]);
        header("Location: likeRecipe.php?msg=Recipe removed successfully");
        exit;
    } catch (PDOException $e) { $error = "Error: " . $e->getMessage(); }
}

// 2. Fetch recipes from singular 'recipe' table
try {
    $recipes = $pdo->query("SELECT id, title, status, like_number, views FROM recipe ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Moderation | Staff</title>
    <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
  <style>
  body { 
    background-color: #F3EAD7; 
    color: #1E2D24; 
    font-family: 'Inter', 'Segoe UI', sans-serif; 
    margin: 0; 
  }

  .main { 
    margin-left: 290px; 
    padding: 40px; 
  }

  .header-box { 
    margin-bottom: 30px; 
  }

  .header-box h2 { 
    font-size: 28px; 
    margin: 0; 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    color: #165540;
  }

  .recipe-card { 
    background: #A3CFAE; 
    border-radius: 14px; 
    padding: 20px; 
    margin-bottom: 18px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    box-shadow: 0 6px 14px rgba(22,85,64,0.25);
    transition: 0.25s ease;
  }

  .recipe-card:hover { 
    transform: translateX(6px); 
    background: #B7DCC0; 
  }

  .recipe-info h4 { 
    margin: 0; 
    font-size: 18px; 
    color: #165540; 
  }

  .recipe-stats { 
    font-size: 13px; 
    color: #3d5f50; 
    margin-top: 6px; 
  }

  .recipe-stats b { 
    color: #165540; 
  }

  .status-badge {
    font-size: 11px;
    font-weight: bold;
    padding: 5px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    margin-left: 10px;
  }

  .status-pending { 
    background: #F4C430; 
    color: #1E2D24; 
  }

  .status-approved { 
    background: #165540; 
    color: white; 
  }

  .status-declined { 
    background: #E74C3C; 
    color: white; 
  }

  .btn-remove { 
    background: transparent; 
    color: #E74C3C; 
    border: 2px solid #E74C3C; 
    padding: 8px 18px; 
    border-radius: 20px; 
    text-decoration: none; 
    font-size: 13px; 
    font-weight: 600;
    transition: all 0.2s ease;
  }

  .btn-remove:hover { 
    background: #E74C3C; 
    color: white; 
    transform: translateY(-2px);
  }

  .success-msg { 
    color: #165540; 
    background: #E6EFE9; 
    padding: 15px; 
    border-radius: 12px; 
    margin-bottom: 20px; 
    border-left: 6px solid #165540; 
    font-weight: 600;
  }

  @media (max-width: 900px) {
    .main {
      margin-left: 0;
      padding: 20px;
    }

    .recipe-card {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
  }
</style>

</head>
<body>
    <?php include __DIR__ . "/../../Common/sidebar.php"; ?>

    <div class="main">
        <div class="header-box">
            <h2>❤️ Recipe Moderation</h2>
            <p style="color: #bbb; margin-top: 5px;">Review user uploads and manage community engagement.</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="success-msg">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <div class="moderation-list">
            <?php foreach ($recipes as $r): ?>
            <div class="recipe-card">
                <div class="recipe-info">
                    <div style="display: flex; align-items: center;">
                        <h4><?= htmlspecialchars($r['title']) ?></h4>
                        <span class="status-badge status-<?= $r['status'] ?>"><?= $r['status'] ?></span>
                    </div>
                    <div class="recipe-stats">
                        Likes: <b><?= $r['like_number'] ?></b> | Views: <b><?= $r['views'] ?></b>
                    </div>
                </div>
                
                <a href="?remove_id=<?= $r['id'] ?>" class="btn-remove" onclick="return confirm('Are you sure you want to remove this recipe?')">Remove</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>