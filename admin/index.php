<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/stats.php';

require_login();

$pageTitle = 'Dashboard';
$stats = dashboard_stats($pdo);

$recent = $pdo->query("
    SELECT 
        a.id,
        a.appointment_date,
        a.status,
        p.name AS patient_name,
        d.name AS doctor_name
    FROM appointments a
    LEFT JOIN patients p ON p.id = a.patient_id
    LEFT JOIN doctors d ON d.id = a.doctor_id
    ORDER BY a.id DESC
    LIMIT 8
")->fetchAll();

include __DIR__ . '/partials/header.php';
?>

<div class="admin-hero">
    <div>
        <h2>Admin Dashboard</h2>
        <p class="muted">
            Website appointment form and admin CRUD are connected to the same database.
        </p>
    </div>
</div>

<div class="grid-4">
    <?php foreach ($stats as $k => $v): ?>
        <div class="stat-card">
            <div class="label"><?= e($k) ?></div>
            <div class="value"><?= e($v) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="table-card">
    <div class="table-head">
        <h3>Recent Appointments</h3>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><?= e($r['id']) ?></td>
                        <td><?= e($r['appointment_date']) ?></td>
                        <td><?= e($r['patient_name']) ?></td>
                        <td><?= e($r['doctor_name']) ?></td>
                        <td>
                            <span class="<?= e(status_badge_class($r['status'])) ?>">
                                <?= e($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>