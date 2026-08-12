<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Pharmacy';

/* Search */
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT *
    FROM pharmacy_items
";

$params = [];

if ($search != '') {

    $sql .= "
        WHERE
            item_name LIKE ?
            OR category LIKE ?
            OR quantity LIKE ?
            OR unit_price LIKE ?
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
        Medicine stock and expiry management.
    </div>

    <div class="toolbar-actions">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Medicine / Category..."
                value="<?= e($_GET['search'] ?? '') ?>">

            <button type="submit" class="btn btn-secondary">
                Search
            </button>

            <?php if(!empty($search)): ?>
                <a href="index.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>

        </form>

        <a class="btn btn-primary" href="form.php">
            Add Medicine
        </a>

    </div>

</div>

<div class="table-card">

    <div class="table-head">
        <h3>All Pharmacy Items</h3>
    </div>

    <div class="table-wrap">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if(count($rows) > 0): ?>

                <?php foreach($rows as $r): ?>

                <tr>

                    <td><?= e($r['id']) ?></td>
                    <td><?= e($r['item_name']) ?></td>
                    <td><?= e($r['category']) ?></td>
                    <td><?= e($r['quantity']) ?></td>
                    <td><?= e($r['unit_price']) ?></td>
                    <td><?= e($r['expiry_date']) ?></td>

                    <td class="actions">

                        <a class="btn btn-secondary"
                           href="form.php?id=<?= e($r['id']) ?>">
                            Edit
                        </a>

                        <a class="btn btn-danger"
                           data-confirm="Delete medicine?"
                           href="delete.php?id=<?= e($r['id']) ?>">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No medicines found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>