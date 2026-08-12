<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {

    // check doctor exists
    $stmt = $pdo->prepare("SELECT id FROM doctors WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->fetch()) {
        $pdo->prepare("DELETE FROM doctors WHERE id = ?")
            ->execute([$id]);
    }
}

header('Location: index.php');
exit;