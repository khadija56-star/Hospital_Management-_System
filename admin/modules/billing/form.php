<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Billing Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'patient_id' => '',
    'bill_no' => '',
    'amount' => '',
    'payment_status' => 'Pending',
    'billing_date' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM billing WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

$patients = $pdo->query('SELECT id, name, phone FROM patients ORDER BY name ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        (int) ($_POST['patient_id'] ?? 0),
        trim($_POST['bill_no'] ?? ''),
        trim($_POST['amount'] ?? ''),
        trim($_POST['payment_status'] ?? 'Pending'),
        trim($_POST['billing_date'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare("
            UPDATE billing
            SET patient_id = ?, bill_no = ?, amount = ?, payment_status = ?, billing_date = ?
            WHERE id = ?
        ")->execute($data);
    } else {
        $pdo->prepare("
            INSERT INTO billing(patient_id, bill_no, amount, payment_status, billing_date, created_at)
            VALUES(?, ?, ?, ?, ?, NOW())
        ")->execute($data);
    }

    header('Location: index.php');
    exit;
}

include __DIR__ . '/../../partials/header.php';
?>

<div class="table-card form-card">
    <div class="table-head">
        <h3><?= $id ? 'Edit Billing Record' : 'Add Billing Record' ?></h3>
    </div>

    <div class="form-body">
        <form method="post" class="grid-2">

            <div class="form-group">
                <label>Patient</label>
                <select class="form-control" name="patient_id" required>
                    <option value="">Select</option>
                    <?php foreach ($patients as $p): ?>
                        <option value="<?= e($p['id']) ?>" <?= (string) $row['patient_id'] === (string) $p['id'] ? 'selected' : '' ?>>
                            <?= e($p['name'] . ' (' . $p['phone'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Bill No</label>
                <input
                    class="form-control"
                    name="bill_no"
                    value="<?= e($row['bill_no']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input
                    class="form-control"
                    type="number"
                    step="0.01"
                    name="amount"
                    value="<?= e($row['amount']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="payment_status">
                    <?php foreach (['Pending', 'Paid'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $row['payment_status'] === $s ? 'selected' : '' ?>>
                            <?= e($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Billing Date</label>
                <input
                    class="form-control"
                    type="date"
                    name="billing_date"
                    value="<?= e($row['billing_date'] ? substr($row['billing_date'], 0, 10) : '') ?>"
                    required
                >
            </div>

            <div class="form-group full-width form-actions">
                <button class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-secondary" href="index.php">Back</a>
            </div>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>