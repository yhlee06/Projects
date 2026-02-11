<?php
require_once 'admin_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../Common/sidebar.css">

    <style>
        body {
            background: #1C352D;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .main {
            margin-left: 280px;
            padding: 40px;
        }

        @media (max-width: 600px) {
            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php include '../Common/sidebar.php'; ?>

<div class="main">
    <h2>Admin Dashboard</h2>
    <p>Welcome to the administrator control panel.</p>
</div>

</body>
</html>
