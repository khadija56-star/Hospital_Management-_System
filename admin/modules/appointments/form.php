<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Appointment Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'patient_id' => '',
    'doctor_id' => '',
    'appointment_date' => '',
    'status' => 'Scheduled',
    'notes' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM appointments WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

$patients = $pdo->query('SELECT id, name, phone FROM patients ORDER BY name ASC')->fetchAll();
$doctors = $pdo->query('SELECT id, name, specialization FROM doctors ORDER BY name ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        (int) ($_POST['patient_id'] ?? 0),
        (int) ($_POST['doctor_id'] ?? 0),
        trim($_POST['appointment_date'] ?? ''),
        trim($_POST['status'] ?? 'Scheduled'),
        trim($_POST['notes'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare('
            UPDATE appointments
            SET patient_id = ?, doctor_id = ?, appointment_date = ?, status = ?, notes = ?
            WHERE id = ?
        ')->execute($data);
    } else {
        $pdo->prepare('
            INSERT INTO appointments(patient_id, doctor_id, appointment_date, status, notes, created_at)
            VALUES(?, ?, ?, ?, ?, NOW())
        ')->execute($data);
    }

    header('Location: index.php');
    exit;
}

include __DIR__ . '/../../partials/header.php';
?>

<div class="table-card form-card">
    <div class="table-head">
        <h3><?= $id ? 'Edit Appointment' : 'Add Appointment' ?></h3>
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
                <label>Doctor</label>
                <select class="form-control" name="doctor_id" required>
                    <option value="">Select</option>
                    <?php foreach ($doctors as $d): ?>
                        <option value="<?= e($d['id']) ?>" <?= (string) $row['doctor_id'] === (string) $d['id'] ? 'selected' : '' ?>>
                            <?= e($d['name'] . ' - ' . $d['specialization']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input
                    class="form-control"
                    type="datetime-local"
                    name="appointment_date"
                    value="<?= e($row['appointment_date'] ? str_replace(' ', 'T', substr($row['appointment_date'], 0, 16)) : '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status">
                    <?php foreach (['Scheduled', 'Completed', 'Cancelled'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $row['status'] === $s ? 'selected' : '' ?>>
                            <?= e($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Notes</label>
                <textarea class="form-control" name="notes" rows="4"><?= e($row['notes']) ?></textarea>
            </div>

            <div class="form-group full-width form-actions">
                <button class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-secondary" href="index.php">Back</a>
            </div>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>