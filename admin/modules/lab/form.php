<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Lab Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'patient_id' => '',
    'test_name' => '',
    'result_summary' => '',
    'status' => 'Pending',
    'test_date' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM lab_tests WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

$patients = $pdo->query("
    SELECT id, name, phone
    FROM patients
    ORDER BY name ASC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        (int) ($_POST['patient_id'] ?? 0),
        trim($_POST['test_name'] ?? ''),
        trim($_POST['result_summary'] ?? ''),
        trim($_POST['status'] ?? 'Pending'),
        trim($_POST['test_date'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare("
            UPDATE lab_tests
            SET patient_id = ?, test_name = ?, result_summary = ?, status = ?, test_date = ?
            WHERE id = ?
        ")->execute($data);
    } else {
        $pdo->prepare("
            INSERT INTO lab_tests(patient_id, test_name, result_summary, status, test_date, created_at)
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
        <h3><?= $id ? 'Edit Lab Test' : 'Add Lab Test' ?></h3>
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
                <label>Test Name</label>
                <input
                    class="form-control"
                    name="test_name"
                    value="<?= e($row['test_name']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status">
                    <?php foreach (['Pending', 'Completed'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $row['status'] === $s ? 'selected' : '' ?>>
                            <?= e($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Test Date</label>
                <input
                    class="form-control"
                    type="date"
                    name="test_date"
                    value="<?= e($row['test_date'] ? substr($row['test_date'], 0, 10) : '') ?>"
                    required
                >
            </div>

            <div class="form-group full-width">
                <label>Result Summary</label>
                <textarea
                    class="form-control"
                    name="result_summary"
                    rows="4"
                ><?= e($row['result_summary']) ?></textarea>
            </div>

            <div class="form-group full-width form-actions">
                <button class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-secondary" href="index.php">Back</a>
            </div>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>