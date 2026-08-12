<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Billing';

/* Search */
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT
        b.*,
        p.name AS patient_name
    FROM billing b
    LEFT JOIN patients p ON p.id = b.patient_id
";

$params = [];

if ($search != '') {

    $sql .= "
        WHERE
            p.name LIKE ?
            OR b.bill_no LIKE ?
            OR b.payment_status LIKE ?
            OR b.id LIKE ?
    ";

    $like = "%{$search}%";

    $params = [
        $like,
        $like,
        $like,
        $like
    ];
}

$sql .= " ORDER BY b.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/header.php';
?>

<div class="toolbar">

    <div class="muted">
        Manage patient bills and payment status.
    </div>

    <div class="toolbar-actions">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Patient / Bill No / Status..."
                value="<?= e($_GET['search'] ?? '') ?>">

            <button type="submit" class="btn btn-secondary">
                Search
            </button>

            <?php if(!empty($search)): ?>
                <a href="index.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>

        </form>

        <a class="btn btn-primary" href="form.php">
            Add Bill
        </a>

    </div>

</div>

<div class="table-card">

    <div class="table-head">
        <h3>All Billing Records</h3>
    </div>

    <div class="table-wrap">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Bill No</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if(count($rows) > 0): ?>

                <?php foreach($rows as $r): ?>

                <tr>

                    <td><?= e($r['id']) ?></td>

                    <td><?= e($r['patient_name']) ?></td>

                    <td><?= e($r['bill_no']) ?></td>

                    <td><?= e($r['amount']) ?></td>

                    <td>
                        <span class="<?= e(status_badge_class($r['payment_status'])) ?>">
                            <?= e($r['payment_status']) ?>
                        </span>
                    </td>

                    <td><?= e($r['billing_date']) ?></td>

                    <td class="actions">
                        <a class="btn btn-secondary" href="form.php?id=<?= e($r['id']) ?>">
                            Edit
                        </a>

                        <a class="btn btn-danger"
                           data-confirm="Delete bill?"
                           href="delete.php?id=<?= e($r['id']) ?>">
                            Delete
                        </a>
                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No billing records found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>