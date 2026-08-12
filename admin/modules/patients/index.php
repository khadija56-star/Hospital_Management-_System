<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Patients';

/* Search */
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT *
    FROM patients
";

$params = [];

if ($search != '') {

    $sql .= "
        WHERE
            name LIKE ?
            OR phone LIKE ?
            OR blood_group LIKE ?
            OR gender LIKE ?
            OR id LIKE ?
    ";

    $like = "%{$search}%";

    $params = [
        $like,
        $like,
        $like,
        $like,
        $like
    ];
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/header.php';
?>

<div class="toolbar">

    <div class="muted">
        Patient records managed from admin.
    </div>

    <div class="toolbar-actions">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Name / Phone / Blood Group..."
                value="<?= e($_GET['search'] ?? '') ?>">

            <button type="submit" class="btn btn-secondary">
                Search
            </button>

            <?php if(!empty($search)): ?>
                <a href="index.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>

        </form>

        <a class="btn btn-primary" href="form.php">
            Add Patient
        </a>

    </div>

</div>

<div class="table-card">

    <div class="table-head">
        <h3>All Patients</h3>
    </div>

    <div class="table-wrap">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Blood</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if(count($rows) > 0): ?>

                <?php foreach($rows as $r): ?>

                <tr>

                    <td><?= e($r['id']) ?></td>
                    <td><?= e($r['name']) ?></td>
                    <td><?= e($r['age']) ?></td>
                    <td><?= e($r['gender']) ?></td>
                    <td><?= e($r['phone']) ?></td>
                    <td><?= e($r['blood_group']) ?></td>

                    <td class="actions">

                        <a class="btn btn-secondary"
                           href="form.php?id=<?= e($r['id']) ?>">
                            Edit
                        </a>

                        <a class="btn btn-danger"
                           data-confirm="Delete patient?"
                           href="delete.php?id=<?= e($r['id']) ?>">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No patients found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>