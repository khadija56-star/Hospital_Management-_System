<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {

    // check item exists
    $stmt = $pdo->prepare("SELECT id FROM pharmacy_items WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->fetch()) {
        $pdo->prepare("DELETE FROM pharmacy_items WHERE id = ?")
            ->execute([$id]);
    }
}

header('Location: index.php');
exit;