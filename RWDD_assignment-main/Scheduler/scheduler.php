<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$logFile = __DIR__ . '/scheduler.log';
$lastRunDate = null;

require '../Common/db.php';

echo "Scheduler started - waiting for 12 AM daily...\n";

while (true) {
    $currentDate = date('Y-m-d');
    $currentHour = (int)date('H');
    $currentMinute = (int)date('i');
    
    // Run at 12:00 AM, only once per day
    if ($currentHour === 0 && $currentMinute === 0 && $lastRunDate !== $currentDate) {
        echo "\n[" . date('Y-m-d H:i:s') . "] Running daily task...\n";

        $stmt = $pdo->query("SELECT id, expiry_date, status FROM food_donation WHERE status != 'expired'");
        $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $expiredCount = 0;

        foreach ($donations as $donation) {
            $expiryDate = $donation['expiry_date'];
            $id = $donation['id'];

            if (strtotime($expiryDate) < strtotime($currentDate)) {
                $updateStmt = $pdo->prepare("UPDATE food_donation SET status = 'expired', modified_date = ? WHERE id = ?"); 
                $updateStmt->execute([date('Y-m-d H:i:s'), $id]);
                $expiredCount++;
                echo "  - Donation ID $id marked as expired\n";
            }
        }

        echo "  - $expiredCount donations updated\n";
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $expiredCount donations marked as expired\n", FILE_APPEND);

        echo "Task completed!\n\n";
        $lastRunDate = $currentDate;
    }

    sleep(60); // Check every minute
}
?>
