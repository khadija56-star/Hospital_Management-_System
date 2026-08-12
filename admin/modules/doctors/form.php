<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Doctor Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'name' => '',
    'specialization' => '',
    'phone' => '',
    'email' => '',
    'schedule' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM doctors WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        trim($_POST['name'] ?? ''),
        trim($_POST['specialization'] ?? ''),
        trim($_POST['phone'] ?? ''),
        trim($_POST['email'] ?? ''),
        trim($_POST['schedule'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare("
            UPDATE doctors
            SET name = ?, specialization = ?, phone = ?, email = ?, schedule = ?
            WHERE id = ?
        ")->execute($data);
    } else {
        $pdo->prepare("
            INSERT INTO doctors(name, specialization, phone, email, schedule, created_at)
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
        <h3><?= $id ? 'Edit Doctor' : 'Add Doctor' ?></h3>
    </div>

    <div class="form-body">
        <form method="post" class="grid-2">

            <div class="form-group">
                <label>Name</label>
                <input
                    class="form-control"
                    name="name"
                    value="<?= e($row['name']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Specialization</label>
                <input
                    class="form-control"
                    name="specialization"
                    value="<?= e($row['specialization']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input
                    class="form-control"
                    name="phone"
                    value="<?= e($row['phone']) ?>"
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    class="form-control"
                    type="email"
                    name="email"
                    value="<?= e($row['email']) ?>"
                >
            </div>

            <div class="form-group full-width">
                <label>Schedule</label>
                <input
                    class="form-control"
                    name="schedule"
                    value="<?= e($row['schedule']) ?>"
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