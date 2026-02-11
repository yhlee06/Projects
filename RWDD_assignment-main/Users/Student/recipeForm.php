<?php
session_start();
require_once __DIR__ . "/../../Common/accessControl.php"; // uses your common guard
require_once __DIR__ . "/../../Common/db.php";

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}

/**
 * Detect ownership column in recipe table:
 * - Prefer user_id if exists
 * - Else fallback to username if exists
 */
function recipeOwnerColumn(PDO $pdo): ?string {
    $cols = $pdo->query("SHOW COLUMNS FROM recipe")->fetchAll(PDO::FETCH_ASSOC);
    $names = array_column($cols, 'Field');
    if (in_array('user_id', $names)) return 'user_id';
    if (in_array('username', $names)) return 'username';
    if (in_array('created_by', $names)) return 'created_by';
    return null;
}

$ownerCol = recipeOwnerColumn($pdo);

// get user_id (only needed if recipe uses user_id)
$user_id = null;
if ($ownerCol === 'user_id') {
    $stmt = $pdo->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user_id = $stmt->fetchColumn();
    if (!$user_id) die("User not found");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;

$title = $prep_time = $cook_time = $description = $ingredients = $instructions = "";
$image = "";
$current = null;

// Load recipe if editing
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM recipe WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$current) die("Recipe not found.");

    // Ownership check (student can only edit own recipe)
    if ($ownerCol === 'user_id' && (int)$current['user_id'] !== (int)$user_id) {
        die("You are not allowed to edit this recipe.");
    }
    if (in_array($ownerCol, ['username','created_by'], true) && ($current[$ownerCol] ?? '') !== $username) {
        die("You are not allowed to edit this recipe.");
    }

    $title        = $current['title'] ?? "";
    $image        = $current['image'] ?? "";
    $prep_time    = $current['prep_time'] ?? "";
    $cook_time    = $current['cook_time'] ?? "";
    $description  = $current['description'] ?? "";
    $ingredients  = $current['ingredients'] ?? "";
    $instructions = $current['instructions'] ?? "";
}

// SAVE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title'] ?? '');
    $prep_time    = trim($_POST['prep_time'] ?? '');
    $cook_time    = trim($_POST['cook_time'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $ingredients  = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');

    if ($title === '') {
        die("Title is required.");
    }

    // Handle image upload (optional)
    $newImageName = $image; // keep old by default
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            die("Image must be JPG, PNG, or WEBP.");
        }

        $newImageName = uniqid("recipe_", true) . "." . $ext;

        // IMPORTANT: this matches Admin path: ../public/recipes/<image>
        $uploadDir = __DIR__ . "/../../public/recipes/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($tmp, $uploadDir . $newImageName)) {
            die("Failed to upload image.");
        }
    }

    if ($isEdit) {
        // When student edits, set status back to pending so Admin re-approves
        $sql = "UPDATE recipe
                SET title=?, image=?, prep_time=?, cook_time=?, description=?, ingredients=?, instructions=?, status='pending'
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $newImageName, $prep_time, $cook_time, $description, $ingredients, $instructions, $id]);

    } else {
        // New recipe -> pending
        if ($ownerCol === 'user_id') {
            $sql = "INSERT INTO recipe (title, image, prep_time, cook_time, description, ingredients, instructions, status, user_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $newImageName, $prep_time, $cook_time, $description, $ingredients, $instructions, $user_id]);
        } elseif ($ownerCol) {
            $sql = "INSERT INTO recipe (title, image, prep_time, cook_time, description, ingredients, instructions, status, $ownerCol)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $newImageName, $prep_time, $cook_time, $description, $ingredients, $instructions, $username]);
        } else {
            // fallback if recipe table has no owner column
            $sql = "INSERT INTO recipe (title, image, prep_time, cook_time, description, ingredients, instructions, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $newImageName, $prep_time, $cook_time, $description, $ingredients, $instructions]);
        }
    }

    header("Location: recipes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $isEdit ? "Edit Recipe" : "Add Recipe"; ?></title>

  <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
  <link rel="stylesheet" href="recipes.css">
</head>
<body>

<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
  <div class="page-top">
    <div>
      <a class="back" href="recipes.php">← Recipes</a>
      <h1 class="page-title"><?php echo $isEdit ? "Edit Recipe" : "Add Recipe"; ?></h1>
      <p class="page-sub">After saving, your recipe will be <b>Pending</b> until Admin approves.</p>
    </div>
  </div>

  <div class="panel">
    <form method="POST" enctype="multipart/form-data" class="recipe-form">

      <div class="form-grid">

        <div class="field">
          <label>Recipe Title</label>
          <input type="text" name="title" required value="<?= htmlspecialchars($recipe['title'] ?? '') ?>">
        </div>

        <div class="field">
          <label>Upload Image</label>
          <input type="file" name="image" accept="image/*">
          <?php if (!empty($recipe['image'])): ?>
            <small class="hint">Current: <?= htmlspecialchars($recipe['image']) ?></small>
          <?php endif; ?>
        </div>

        <div class="field">
          <label>Prep Time (min)</label>
          <input type="number" name="prep_time" min="0" value="<?= htmlspecialchars($recipe['prep_time'] ?? '') ?>">
        </div>

        <div class="field">
          <label>Cook Time (min)</label>
          <input type="number" name="cook_time" min="0" value="<?= htmlspecialchars($recipe['cook_time'] ?? '') ?>">
        </div>

        <div class="field full">
          <label>Description</label>
          <textarea name="description" rows="3" placeholder="Short description..."><?= htmlspecialchars($recipe['description'] ?? '') ?></textarea>
        </div>

        <div class="field full">
          <label>Ingredients</label>
          <textarea name="ingredients" rows="5" placeholder="List ingredients..."><?= htmlspecialchars($recipe['ingredients'] ?? '') ?></textarea>
        </div>

        <div class="field full">
          <label>Instructions</label>
          <textarea name="instructions" rows="6" placeholder="Write steps..."><?= htmlspecialchars($recipe['instructions'] ?? '') ?></textarea>
        </div>

      </div>

      <div class="form-actions">
        <button class="btn primary" type="submit">Save</button>
        <a class="btn ghost" href="recipes.php">Cancel</a>
      </div>

    </form>

  </div>
</div>

</body>
</html>
