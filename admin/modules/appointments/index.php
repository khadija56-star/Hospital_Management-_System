<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Appointments';

/* Search */
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT
        a.*,
        p.name AS patient_name,
        d.name AS doctor_name
    FROM appointments a
    LEFT JOIN patients p ON p.id = a.patient_id
    LEFT JOIN doctors d ON d.id = a.doctor_id
";

$params = [];

if ($search != '') {

    $sql .= "
        WHERE
            p.name LIKE ?
            OR d.name LIKE ?
            OR a.status LIKE ?
            OR a.id LIKE ?
    ";

    $like = "%{$search}%";

    $params = [
        $like,
        $like,
        $like,
        $like
    ];
}

$sql .= " ORDER BY a.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/header.php';
?>

<div class="toolbar">

    <div class="muted">
        Appointments from website and admin both appear here.
    </div>

    <div class="toolbar-actions">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Patient / Doctor / Status..."
                value="<?= e($_GET['search'] ?? '') ?>">

            <button type="submit" class="btn btn-secondary">
                Search
            </button>

            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>

        </form>

        <a class="btn btn-primary" href="form.php">
            Add Appointment
        </a>

    </div>

</div>

<div class="table-card">

    <div class="table-head">
        <h3>All Appointments</h3>
    </div>

    <div class="table-wrap">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if(count($rows) > 0): ?>

                <?php foreach ($rows as $r): ?>

                <tr>

                    <td><?= e($r['id']) ?></td>

                    <td><?= e($r['patient_name']) ?></td>

                    <td><?= e($r['doctor_name']) ?></td>

                    <td><?= e($r['appointment_date']) ?></td>

                    <td>
                        <span class="<?= e(status_badge_class($r['status'])) ?>">
                            <?= e($r['status']) ?>
                        </span>
                    </td>

                    <td><?= e($r['notes']) ?></td>

                    <td class="actions">

                        <a class="btn btn-secondary"
                           href="form.php?id=<?= e($r['id']) ?>">
                            Edit
                        </a>

                        <a class="btn btn-danger"
                           data-confirm="Delete appointment?"
                           href="delete.php?id=<?= e($r['id']) ?>">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No appointments found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>