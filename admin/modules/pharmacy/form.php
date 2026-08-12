<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$pageTitle = 'Pharmacy Form';
$id = (int) ($_GET['id'] ?? 0);

$row = [
    'item_name'   => '',
    'category'    => '',
    'quantity'    => '',
    'unit_price'  => '',
    'expiry_date' => ''
];

if ($id) {
    $st = $pdo->prepare('SELECT * FROM pharmacy_items WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        trim($_POST['item_name'] ?? ''),
        trim($_POST['category'] ?? ''),
        trim($_POST['quantity'] ?? ''),
        trim($_POST['unit_price'] ?? ''),
        trim($_POST['expiry_date'] ?? '')
    ];

    if ($id) {
        $data[] = $id;

        $pdo->prepare("
            UPDATE pharmacy_items
            SET item_name = ?, category = ?, quantity = ?, unit_price = ?, expiry_date = ?
            WHERE id = ?
        ")->execute($data);
    } else {
        $pdo->prepare("
            INSERT INTO pharmacy_items(item_name, category, quantity, unit_price, expiry_date, created_at)
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
        <h3><?= $id ? 'Edit Pharmacy Item' : 'Add Pharmacy Item' ?></h3>
    </div>

    <div class="form-body">
        <form method="post" class="grid-2">

            <div class="form-group">
                <label>Medicine Name</label>
                <input
                    class="form-control"
                    name="item_name"
                    value="<?= e($row['item_name']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Category</label>
                <input
                    class="form-control"
                    name="category"
                    value="<?= e($row['category']) ?>"
                >
            </div>

            <div class="form-group">
                <label>Quantity</label>
                <input
                    class="form-control"
                    type="number"
                    name="quantity"
                    value="<?= e($row['quantity']) ?>"
                >
            </div>

            <div class="form-group">
                <label>Unit Price</label>
                <input
                    class="form-control"
                    type="number"
                    step="0.01"
                    name="unit_price"
                    value="<?= e($row['unit_price']) ?>"
                >
            </div>

            <div class="form-group full-width">
                <label>Expiry Date</label>
                <input
                    class="form-control"
                    type="date"
                    name="expiry_date"
                    value="<?= e($row['expiry_date'] ? substr($row['expiry_date'], 0, 10) : '') ?>"
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