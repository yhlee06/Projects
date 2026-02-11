<?php
session_start();
require_once __DIR__ . "/../../Common/accessControl.php";
require_once __DIR__ . "/../../Common/db.php";

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: /RWDD_assignment/LoginSignUp/login.php");
    exit;
}

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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: recipes.php");
    exit;
}

if ($ownerCol === 'user_id') {
    $stmt = $pdo->prepare("DELETE FROM recipe WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
} elseif ($ownerCol) {
    $stmt = $pdo->prepare("DELETE FROM recipe WHERE id = ? AND $ownerCol = ?");
    $stmt->execute([$id, $username]);
} else {
    // fallback (not ideal)
    $stmt = $pdo->prepare("DELETE FROM recipe WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: recipes.php");
exit;
