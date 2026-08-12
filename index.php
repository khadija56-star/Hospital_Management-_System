<?php
$pageTitle = 'Home';
include __DIR__ . '/public/partials/header.php';

$doctors = $pdo->query("SELECT * FROM doctors ORDER BY id DESC LIMIT 8")->fetchAll();
?>

<section class="hero">
    <div class="hero-grid">

        <div class="hero-copy">
            <span class="badge">Trusted Multi-Specialty Hospital</span>

            <h1>
                Green Life General Hospital provides modern care, trusted specialists, and easy online appointments.
            </h1>

            <p>
                Patients can explore departments, view doctors, and request appointments from one simple hospital website.
            </p>

            <div class="hero-actions">
                <a class="btn btn-dark" href="<?= e(url('appointment.php')) ?>">
                    Book Appointment
                </a>
            </div>
        </div>

        <div class="hero-card">
            <h3>Hospital Facilities</h3>

            <ul class="hero-list">
                <li>24/7 Emergency Support</li>
                <li>Experienced Specialist Doctors</li>
                <li>Digital Appointment Service</li>
                <li>Modern Operation Theatre</li>
                <li>In-house Pharmacy & Lab</li>
                <li>Clean Cabin, Ward and ICU Care</li>
            </ul>
        </div>

    </div>
</section>

<section class="section">
    <div class="container">

        <div class="section-head">
            <div>
                <span class="badge">Hospital Services</span>
                <h2>Complete care for every patient</h2>
            </div>
        </div>

        <div class="cards-3">

            <div class="info-card">
                <h3>Emergency Care</h3>
                <p>Immediate emergency response with trained staff, ambulance support, and rapid critical care.</p>
            </div>

            <div class="info-card">
                <h3>Specialist Doctors</h3>
                <p>Consult experienced doctors in cardiology, medicine, orthopedics, gynecology, pediatrics, and surgery.</p>
            </div>

            <div class="info-card">
                <h3>Lab & Pharmacy</h3>
                <p>Diagnostic tests, pathology services, and a fully stocked pharmacy under one roof.</p>
            </div>

        </div>

    </div>
</section>

<section class="section">
    <div class="container">

        <div class="section-head">
            <div>
                <span class="badge">Our Doctors</span>
                <h2>Our medical specialists</h2>
            </div>

            <a class="btn btn-primary" href="<?= e(url('doctors.php')) ?>">
                View All
            </a>
        </div>

        <div class="cards-4">

            <?php foreach ($doctors as $d): ?>
                <div class="info-card">
                    <h3><?= e($d['name']) ?></h3>
                    <p><strong><?= e($d['specialization']) ?></strong></p>
                    <p><?= e($d['phone']) ?></p>
                    <p><?= e($d['email']) ?></p>
                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<section class="section">
    <div class="container">

        <div class="appointment-box">
            <div class="section-head">
                <div>
                    <span class="badge">Quick Access</span>
                    <h2>Need an appointment?</h2>

                    <p class="muted">
                        Use the public form. It goes directly into the same database
                        that the admin panel uses.
                    </p>
                </div>

                <a class="btn btn-primary" href="<?= e(url('appointment.php')) ?>">
                    Open Appointment Form
                </a>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/public/partials/footer.php'; ?>