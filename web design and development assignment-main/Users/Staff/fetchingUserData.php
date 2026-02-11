<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

// Fetch users from the 'user' table
try {
    // Fetch users with real data from your table
    $query = "SELECT name, username, role, user_type, status FROM user ORDER BY id DESC";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | Zero Waste</title>
    <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
    <style>
        /* Exact background from your dashboard */
        body { background-color: #3e3e3e; color: #ffffff; font-family: 'Inter', 'Segoe UI', sans-serif; margin: 0; }
        .main { margin-left: 260px; padding: 40px; }

        .header-box { margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; }
        .header-box h2 { font-size: 28px; margin: 0; display: flex; align-items: center; gap: 12px; }

        /* Modern Table Card */
        .table-container { 
            background: #4a4a4a; 
            border-radius: 16px; 
            padding: 20px; 
            border: 2px solid #00cc99; /* Green border to match your dashboard request */
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        
        /* Table Head */
        th { 
            text-align: left; 
            color: #00cc99; 
            font-size: 13px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            padding: 15px;
            border-bottom: 2px solid #555;
        }

        /* Table Body */
        td { padding: 18px 15px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        .user-name { font-weight: 600; color: #fff; }
        .user-handle { color: #bbb; font-size: 12px; }

        /* Badges for Role and Status */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            background: rgba(255,255,255,0.05);
            color: #ccc;
        }
        
        .role-staff { color: #7b68ee; border: 1px solid #7b68ee; }
        .role-donor { color: #ffa500; border: 1px solid #ffa500; }
        
        .status-active { color: #00cc99; background: rgba(0, 204, 153, 0.1); border: 1px solid #00cc99; }
        
        /* Hover Effect */
        tr:hover { background: rgba(255,255,255,0.03); }
        
        .na-text { color: #777; font-style: italic; font-size: 12px; }
    </style>
</head>
<body>
    <?php include __DIR__ . "/../../Common/sidebar.php"; ?>

    <div class="main">
        <div class="header-box">
            <h2>👥 User Information</h2>
            <p style="color: #bbb; margin-top: 5px;">A full directory of all registered community members and roles.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Identity</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>User Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="user-name"><?= htmlspecialchars((string)($u['name'] ?? 'Unknown')) ?></div>
                        </td>
                        <td><span class="user-handle">@<?= htmlspecialchars((string)($u['username'] ?? 'user')) ?></span></td>
                        <td>
                            <span class="badge <?= (strpos($u['role'], 'donor') !== false) ? 'role-donor' : 'role-staff' ?>">
                                <?= strtoupper(htmlspecialchars((string)$u['role'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if(!empty($u['user_type']) && $u['user_type'] != 'N/A'): ?>
                                <span class="badge"><?= htmlspecialchars((string)$u['user_type']) ?></span>
                            <?php else: ?>
                                <span class="na-text">Not Specified</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge status-active">
                                <?= strtoupper(htmlspecialchars((string)($u['status'] ?? 'ACTIVE'))) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
