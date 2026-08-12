<?php
$pageTitle = 'Doctors';
include __DIR__ . '/public/partials/header.php';

$rows = $pdo->query("SELECT * FROM doctors ORDER BY name ASC")->fetchAll();
$specializations = $pdo->query("SELECT DISTINCT specialization FROM doctors ORDER BY specialization ASC")->fetchAll();
?>

<section class="page-hero compact-hero">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge">Doctors</span>
            <h1>Meet the specialist doctors of Green Life General Hospital</h1>
            <p>
                Our experienced consultants serve cardiology, medicine, surgery,
                pediatrics, gynecology, dermatology, and many other departments.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="doctor-top-panel">
            <div>
                <span class="badge">Departments</span>
                <h2>Specialist Departments</h2>
            </div>

            <ul class="dept-list">
                <?php foreach ($specializations as $s): ?>
                    <li><?= e($s['specialization']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="cards-3 doctor-cards">
            <?php foreach ($rows as $r): ?>
                <div class="info-card doctor-card">
                    <h3><?= e($r['name']) ?></h3>

                    <p><strong><?= e($r['specialization']) ?></strong></p>
                    <p>Phone: <?= e($r['phone']) ?></p>
                    <p>Email: <?= e($r['email']) ?></p>
                    <p>Schedule: <?= e($r['schedule']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include __DIR__ . '/public/partials/footer.php'; ?>