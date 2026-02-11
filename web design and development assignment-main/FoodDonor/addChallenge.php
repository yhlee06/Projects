<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_challenge'])) {
    
    $name = $_POST['challenge_name'];
    $desc = $_POST['description'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $points = (int)$_POST['points_reward'];
    $staff_id = $_SESSION['user_id']; 

    try {
        
        $sql = "INSERT INTO challenge (created_by, challenge_name, description, start_date, end_date, points_reward) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$staff_id, $name, $desc, $start, $end, $points]);
        
        $success = "New challenge published successfully!";
    } catch (PDOException $e) {
        $error = "Failed to add challenge: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Challenge | Staff</title>
    <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
    <style>
        body { background: #262626; color: white; font-family: 'Segoe UI', sans-serif; }
        .main { margin-left: 280px; padding: 40px; }
        .form-container { background: #333; padding: 30px; border-radius: 12px; max-width: 600px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; background: #1a1a1a; border: 1px solid #444; color: white; border-radius: 5px; }
        .btn-submit { background: #00cc99; color: black; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #00cc99; color: #00cc99; }
    </style>
</head>
<body>
    <?php include __DIR__ . "/../../Common/sidebar.php"; ?>
    
    <div class="main">
        <h2>🏆 Create New Challenge</h2>
        <?php if (isset($success)) echo "<div class='alert'>$success</div>"; ?>
        <div class="form-container">
            <form method="POST">
                <input type="text" name="challenge_name" placeholder="Challenge Name" required>
                <textarea name="description" rows="4" placeholder="Describe the rules..."></textarea>
                <div style="display:flex; gap:10px;">
                    <input type="date" name="start_date" required>
                    <input type="date" name="end_date" required>
                </div>
                <input type="number" name="points_reward" placeholder="Points Reward (e.g. 50)" required>
                <button type="submit" name="submit_challenge" class="btn-submit">Publish Challenge</button>
            </form>
        </div>
    </div>
</body>
</html>