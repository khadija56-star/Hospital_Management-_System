<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Patient Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'name' => '',
    'age' => '',
    'gender' => 'Male',
    'phone' => '',
    'blood_group' => '',
    'address' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM patients WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        trim($_POST['name'] ?? ''),
        (int) ($_POST['age'] ?? 0),
        trim($_POST['gender'] ?? 'Male'),
        trim($_POST['phone'] ?? ''),
        trim($_POST['blood_group'] ?? ''),
        trim($_POST['address'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare("
            UPDATE patients
            SET name = ?, age = ?, gender = ?, phone = ?, blood_group = ?, address = ?
            WHERE id = ?
        ")->execute($data);
    } else {
        $pdo->prepare("
            INSERT INTO patients(name, age, gender, phone, blood_group, address, created_at)
            VALUES(?, ?, ?, ?, ?, ?, NOW())
        ")->execute($data);
    }

    header('Location: index.php');
    exit;
}

include __DIR__ . '/../../partials/header.php';
?>

<div class="table-card form-card">
    <div class="table-head">
        <h3><?= $id ? 'Edit Patient' : 'Add Patient' ?></h3>
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
                <label>Age</label>
                <input
                    class="form-control"
                    type="number"
                    name="age"
                    value="<?= e($row['age']) ?>"
                >
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select class="form-control" name="gender">
                    <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                        <option value="<?= e($g) ?>" <?= $row['gender'] === $g ? 'selected' : '' ?>>
                            <?= e($g) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                <label>Blood Group</label>
                <input
                    class="form-control"
                    name="blood_group"
                    value="<?= e($row['blood_group']) ?>"
                >
            </div>

            <div class="form-group">
                <label>Address</label>
                <input
                    class="form-control"
                    name="address"
                    value="<?= e($row['address']) ?>"
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