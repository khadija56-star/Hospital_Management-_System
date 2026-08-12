<?php
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php?redirect=appointments.php");
    exit;
}

$pageTitle = 'Appointment';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientName = trim($_POST['patient_name'] ?? '');
    $age = (int) ($_POST['age'] ?? 0);
    $gender = trim($_POST['gender'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $doctorId = (int) ($_POST['doctor_id'] ?? 0);
    $date = trim($_POST['appointment_date'] ?? '');

    if ($patientName && $phone && $doctorId && $date) {
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        $patientId = $stmt->fetchColumn();

        if (!$patientId) {
            $stmt = $pdo->prepare("
                INSERT INTO patients(name, age, gender, phone, blood_group, address, created_at)
                VALUES(?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $patientName,
                $age,
                $gender,
                $phone,
                trim($_POST['blood_group'] ?? ''),
                trim($_POST['address'] ?? '')
            ]);

            $patientId = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare("
            INSERT INTO appointments(patient_id, doctor_id, appointment_date, status, notes, created_at)
            VALUES(?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $patientId,
            $doctorId,
            $date,
            'Scheduled',
            trim($_POST['notes'] ?? '')
        ]);

        $success = 'Appointment booked successfully. Admin panel will show it instantly.';
    } else {
        $error = 'Please fill required fields.';
    }
}

$doctors = $pdo->query("SELECT id, name, specialization FROM doctors ORDER BY name ASC")->fetchAll();

include __DIR__ . '/public/partials/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <div class="page-hero-content">
            <span class="badge">Appointment Form</span>
            <h1>Book an appointment online</h1>
            <p>
                Fill in the patient details, choose a doctor, and submit your appointment request.
                The form is directly connected to the same MySQL database used by the admin panel.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="appointment-wrapper">

            <?php if ($success): ?>
                <div class="alert success"><?= e($success) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" class="appointment-form">
                <div class="form-grid">

                    <div class="form-group">
                        <label for="patient_name">Patient Name *</label>
                        <input id="patient_name" class="form-control" name="patient_name" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone *</label>
                        <input id="phone" class="form-control" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age</label>
                        <input id="age" class="form-control" type="number" name="age">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" class="form-control" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <input id="blood_group" class="form-control" name="blood_group">
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor *</label>
                        <select id="doctor_id" class="form-control" name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= e($d['id']) ?>">
                                    <?= e($d['name'] . ' - ' . $d['specialization']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">Appointment Date *</label>
                        <input id="appointment_date" class="form-control" type="datetime-local" name="appointment_date" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input id="address" class="form-control" name="address">
                    </div>

                    <div class="form-group full-width">
                        <label for="notes">Notes</label>
                        <textarea id="notes" class="form-control" name="notes" rows="4"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <button class="btn btn-primary" type="submit">Submit Appointment</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</section>

<?php include __DIR__ . '/public/partials/footer.php'; ?>