<?php
require_once __DIR__ . '/../../../config/database.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT id FROM billing WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->fetch()) {
        $pdo->prepare("DELETE FROM billing WHERE id = ?")
            ->execute([$id]);
    }
}

header('Location: index.php');
exit;