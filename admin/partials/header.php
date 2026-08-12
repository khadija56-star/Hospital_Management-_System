<?php
require_once __DIR__ . '/../../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<style>
    /* ==========================================
   Toolbar
========================================== */

.toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:20px;
}

.toolbar-actions{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

/* ==========================================
   Search Form
========================================== */

.search-form{
    display:flex;
    align-items:center;
    gap:10px;
}

.search-form .form-control{
    width:260px;
    height:40px;
    padding:8px 12px;
    border:1px solid #ced4da;
    border-radius:5px;
    font-size:14px;
    outline:none;
    transition:.3s;
}

.search-form .form-control:focus{
    border-color:#0d6efd;
    box-shadow:0 0 5px rgba(13,110,253,.25);
}

/* ==========================================
   Buttons
========================================== */

.search-form .btn{
    height:40px;
    padding:0 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
}

.btn-primary{
    white-space:nowrap;
}

.btn-secondary{
    white-space:nowrap;
}

/* ==========================================
   Responsive
========================================== */

@media (max-width:768px){

    .toolbar{
        flex-direction:column;
        align-items:flex-start;
    }

    .toolbar-actions{
        width:100%;
        flex-direction:column;
        align-items:stretch;
    }

    .search-form{
        width:100%;
        flex-direction:column;
    }

    .search-form .form-control{
        width:100%;
    }

    .search-form .btn,
    .toolbar-actions .btn{
        width:100%;
    }

}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body class="admin-body">

<div class="admin-layout">

    <aside class="admin-sidebar">
        <div class="admin-logo">HMS</div>

        <h2><?= e(APP_NAME) ?></h2>
        <p class="muted">Connected Admin Panel</p>

        <nav class="admin-menu">
            <a href="<?= e(url('index.php')) ?>">Public Website</a>
            <a href="<?= e(url('admin/index.php')) ?>">Dashboard</a>
            <a href="<?= e(url('admin/modules/patients/index.php')) ?>">Patients</a>
            <a href="<?= e(url('admin/modules/doctors/index.php')) ?>">Doctors</a>
            <a href="<?= e(url('admin/modules/appointments/index.php')) ?>">Appointments</a>
            <a href="<?= e(url('admin/modules/billing/index.php')) ?>">Billing</a>
            <a href="<?= e(url('admin/modules/pharmacy/index.php')) ?>">Pharmacy</a>
            <a href="<?= e(url('admin/modules/lab/index.php')) ?>">Lab</a>
            <a href="<?= e(url('logout.php')) ?>">Logout</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <h1><?= e($pageTitle ?? 'Admin Panel') ?></h1>
                <p class="muted">Welcome, <?= e($_SESSION['admin_name'] ?? 'Admin') ?></p>
            </div>

            <div class="admin-user">
                <?= e($_SESSION['admin_username'] ?? 'admin') ?>
            </div>
        </div>