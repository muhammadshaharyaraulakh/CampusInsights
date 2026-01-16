<?php
require __DIR__ . "/../includes/header.php";

/* ==========================
   ACCESS CONTROL
========================== */
if (!isset($_SESSION['id'])) {
    header("Location: /auth/login/login.php");
    exit;
}

$user_id = $_SESSION['id'];
// ==========================
// FETCH ACCOUNT CREATION DATE
// ==========================
try {
    $stmtUser = $connection->prepare("SELECT created_at FROM user WHERE id = :id LIMIT 1");
    $stmtUser->execute(['id' => $user_id]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $accountCreated = $userData['created_at'] ?? null;
} catch (PDOException $e) {
    $accountCreated = null;
}


/* ==========================
   FETCH USER ACTIVITY LOGS
========================== */
try {
    $stmt = $connection->prepare("
        SELECT activity_type, details, ip_address, user_agent, created_at 
        FROM ActivityLog 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $logs = [];
}
?>

<section>
    <div class="container-activity container">
        <div class="header-log">
            <h1 class="text-primary">Activity <span class="text-danger"> Log</span></h1>
        </div>
        <?php if ($accountCreated): ?>
            <p class="creation-date">
                <p class="text-primary">Account Created on <span class="text-danger"><?= date("d M Y", strtotime($accountCreated)) ?></span> </p> 
            </p>
        <?php endif; ?>
        <?php if (empty($logs)): ?>
            <p>No activity yet.</p>
        <?php else: ?>
            <div class="activity-log">

                <?php foreach ($logs as $log):
                    $details = json_decode($log['details'], true);
                ?>

                    <div class="log-card fade-in">
                        <div class="log-header">
                            <span class="log-type"><?= htmlspecialchars($log['activity_type']) ?></span>
                            <span><?= date('d M Y H:i', strtotime($log['created_at'])) ?></span>
                        </div>
                        <div class="log-details">
                            <?php if (isset($details['device_summary'])): ?>

                                <i class="fas fa-desktop"></i> Accessed via:
                                <strong><?= htmlspecialchars($details['device_summary']) ?></strong>

                            <?php elseif (isset($details['survey_name'])): ?>

                                <i class="fas fa-file-alt"></i> Started Survey:
                                <strong><?= htmlspecialchars($details['survey_name']) ?></strong>

                            <?php elseif ($log['details'] && $log['activity_type'] !== 'login' && $log['activity_type'] !== 'logout'): ?>

                                <i class="fas fa-info-circle"></i> Details:
                                <?= htmlspecialchars(substr(json_encode($details), 0, 100)) ?>...

                            <?php endif; ?>
                        </div>
                        <?php if ($log['ip_address']): ?>
                            <div class="log-details">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>IP:</strong> <?= htmlspecialchars($log['ip_address']) ?>
                            </div>
                        <?php endif; ?>
                        <details class="log-details" style="margin-top: 5px; cursor: pointer; color: var(--color-gray);">
                            <summary style="font-weight: 600; font-size: 0.85rem;">Show Raw Data</summary>
                            <div style="padding-left: 10px; font-size: 0.8rem;">
                                <strong>Full Agent:</strong> <?= htmlspecialchars($log['user_agent']) ?>
                            </div>
                        </details>

                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
require __DIR__ . "/../includes/footer.php";
?>