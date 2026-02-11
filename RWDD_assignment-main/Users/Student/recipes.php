<?php
session_start();
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}

// detect owner col
function recipeOwnerColumn(PDO $pdo): ?string {
    $cols = $pdo->query("SHOW COLUMNS FROM recipe")->fetchAll(PDO::FETCH_ASSOC);
    $names = array_column($cols, 'Field');
    if (in_array('user_id', $names)) return 'user_id';
    if (in_array('username', $names)) return 'username';
    if (in_array('created_by', $names)) return 'created_by';
    return null;
}
$ownerCol = recipeOwnerColumn($pdo);

$user_id = null;
if ($ownerCol === 'user_id') {
    $stmt = $pdo->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user_id = $stmt->fetchColumn();
}

$status = $_GET['status'] ?? 'all'; // all|pending|approved|declined
$allowed = ['all','pending','approved','declined'];
if (!in_array($status, $allowed)) $status = 'all';

$q = trim($_GET['q'] ?? '');

$where = [];
$params = [];

// ownership
if ($ownerCol === 'user_id') {
    $where[] = "user_id = ?";
    $params[] = $user_id;
} elseif ($ownerCol) {
    $where[] = "$ownerCol = ?";
    $params[] = $username;
}

// status filter
if ($status !== 'all') {
    $where[] = "status = ?";
    $params[] = $status;
}

// search
if ($q !== '') {
    $where[] = "title LIKE ?";
    $params[] = "%$q%";
}

$sql = "SELECT id, title, image, prep_time, cook_time, status, created_at
        FROM recipe";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// helper: build filter links and keep q
function filterLink(string $st, string $q): string {
    $params = ['status' => $st];
    if ($q !== '') $params['q'] = $q;
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Recipes</title>
  <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
  <link rel="stylesheet" href="recipes.css">
</head>
<body>

<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

<div class="main">
  <div class="page-top">
    <div>
      <h1 class="page-title">My Recipes</h1>
      <p class="page-sub">View your Pending / Approved / Declined recipes.</p>
    </div>

    <div class="top-actions">
    <form class="search" method="GET">
      <img src="/RWDD_assignment/public/icons/search.png" alt="Search" class="search-icon" width='25' height='25'>
      <input type="text" name="q"
            value="<?php echo htmlspecialchars($q); ?>"
            placeholder="Search by title...">
      <input type="hidden" name="status"
            value="<?php echo htmlspecialchars($status); ?>">
    </form>

      <!-- Make it look like Back to Dashboard -->
      <a class="btn primary add-btn" href="recipeForm.php">+ Add Recipe</a>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div class="panel-title">Recipes</div>

      <div class="filters">
        <a class="pill pill-all <?php echo $status==='all'?'active':''; ?>" href="<?php echo filterLink('all', $q); ?>">All</a>
        <a class="pill pill-pending <?php echo $status==='pending'?'active':''; ?>" href="<?php echo filterLink('pending', $q); ?>">Pending</a>
        <a class="pill pill-approved <?php echo $status==='approved'?'active':''; ?>" href="<?php echo filterLink('approved', $q); ?>">Approved</a>
        <a class="pill pill-declined <?php echo $status==='declined'?'active':''; ?>" href="<?php echo filterLink('declined', $q); ?>">Declined</a>
      </div>
    </div>

    <div class="list">
      <?php if (!$rows): ?>
        <div class="empty">No recipes found.</div>
      <?php else: ?>
        <?php foreach ($rows as $r): 
          $st = strtolower($r['status'] ?? '');
          if (!in_array($st, ['pending','approved','declined'])) $st = 'pending';
        ?>
          <div class="row-card">
            <div class="thumb">
              <?php if (!empty($r['image'])): ?>
                <img src="/RWDD_assignment/public/recipes/<?php echo htmlspecialchars($r['image']); ?>" alt="">
              <?php endif; ?>
            </div>

            <div class="row-info">
              <div class="row-title"><?php echo htmlspecialchars($r['title']); ?></div>

              <div class="row-meta">
                <span class="status-badge <?php echo 'status-' . $st; ?>">
                  <?php echo ucfirst($st); ?>
                </span>

                <span class="dot">•</span>
                <span>Prep: <?php echo htmlspecialchars($r['prep_time']); ?> min</span>
                <span class="dot">•</span>
                <span>Cook: <?php echo htmlspecialchars($r['cook_time']); ?> min</span>
              </div>
            </div>

            <div class="row-actions">
              <a class="btn small" href="recipeForm.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
              <a class="btn small danger" href="recipeDelete.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this recipe?');">Delete</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
