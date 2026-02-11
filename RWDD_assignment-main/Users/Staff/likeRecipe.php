<?php
session_start();
require_once __DIR__ . "/../../Common/db.php"; 
require_once __DIR__ . "/../../Common/accessControl.php"; 

try {
    $stmt = $pdo->query("SELECT id, title, status, created_at FROM recipe ORDER BY created_at DESC");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $msgCount = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
} catch (PDOException $e) {
    $recipes = [];
    $msgCount = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipes | Zero Waste Staff</title>
    <link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">
    <style>
        body { background-color: #F3EAD7; color: #1E2D24; font-family: 'Inter', sans-serif; margin: 0; overflow-x: hidden; }
        
        /* ===== DESKTOP LAYOUT ===== */
        .main { margin-left: 290px; padding: 40px; transition: 0.3s ease; }
        .header-section h2 { font-size: 32px; color: #165540; margin: 0; }
        .table-card { background: #A3CFAE; border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(22,85,64,0.25); overflow-x: auto; margin-top: 20px; }
        
        table { width: 100%; border-collapse: collapse; min-width: 500px; }
        th { text-align: left; padding: 16px; color: #165540; border-bottom: 2px solid #88B393; font-size: 13px; text-transform: uppercase; }
        td { padding: 18px; border-bottom: 1px solid #B7DCC0; font-size: 15px; color: #1E2D24; }
        
        .badge { padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .badge-approved { background: #165540; color: white; }
        .badge-pending { background: #FFD966; color: #1E2D24; }

        /* ===== MOBILE NAVIGATION BAR ===== */
        .mobile-header {
            display: none; 
            background: #165540;
            color: white;
            padding: 15px 20px;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .menu-btn {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            border: none;
            color: white;
            font-size: 20px;
        }

        /* ===== RESPONSIVE QUERIES ===== */
        @media (max-width: 992px) {
            .mobile-header { display: flex; }
            
            .main { 
                margin-left: 0; 
                padding: 20px; 
            }

            /* Sync with Sidebar.php toggle logic */
            .sidebar { 
                transform: translateX(-100%); 
                position: fixed;
                z-index: 1001;
                transition: transform 0.3s ease;
                display: block !important;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .header-section h2 { font-size: 24px; }
            
            .table-card {
                padding: 15px;
                border-radius: 12px;
            }
            
            td, th { padding: 12px; font-size: 14px; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . "/../../Common/sidebar.php"; ?>

    <div class="main">
        <div class="header-section">
            <h2>👨‍🍳 Recipe Management</h2>
            <p>You have <strong><?= (int)$msgCount ?></strong> approved feedback messages.</p>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Recipe Name</th>
                        <th>Status</th>
                        <th>Publish Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recipes)): ?>
                        <tr><td colspan="3" style="text-align:center;">No recipes found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recipes as $r): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($r['title']) ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($r['status']) ?>">
                                    <?= htmlspecialchars($r['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Standardized Toggle Script for Mobile Sidebar
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.querySelector('.sidebar');

        if (menuBtn && sidebar) {
            menuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (sidebar.classList.contains('active') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>