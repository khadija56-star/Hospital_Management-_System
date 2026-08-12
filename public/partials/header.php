<?php
require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>

<header class="site-header">
    <div class="container">
        <div class="nav">

            <a class="brand" href="<?= e(url('index.php')) ?>">
                Green Life Hospital
            </a>

            <div class="nav-links">

                <a href="<?= e(url('index.php')) ?>">Home</a>

                <a href="<?= e(url('services.php')) ?>">Services</a>

                <a href="<?= e(url('doctors.php')) ?>">Doctors</a>

                <?php if(isset($_SESSION['user_id'])): ?>

                    <a href="<?= e(url('appointment.php')) ?>">Appointment</a>

                    <a href="<?= e(url('auth/logout.php')) ?>">Logout</a>

                <?php else: ?>

                    <a href="<?= e(url('auth/login.php')) ?>">Login</a>

                <?php endif; ?>

            </div>

        </div>
    </div>
</header>

<main>