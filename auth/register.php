<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Register";

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if (
        $name == '' ||
        $email == '' ||
        $phone == '' ||
        $password == '' ||
        $confirm == ''
    ) {

        $error = "Please fill all fields.";

    } elseif ($password != $confirm) {

        $error = "Passwords do not match.";

    } else {

        $check = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {

            $error = "Email already exists.";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users(name,email,phone,password)
                VALUES(?,?,?,?)
            ");

            $stmt->execute([
                $name,
                $email,
                $phone,
                $hash
            ]);

            $_SESSION['success'] = "Registration successful. Please login.";

            header("Location: login.php");
            exit;
        }

    }

}

include __DIR__ . '/../public/partials/header.php';
?>

<section class="section">

    <div class="container" style="max-width:600px;">

        <h2>Create Account</h2>

        <?php if($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" class="appointment-form">

            <div class="form-group">
                <label>Full Name</label>
                <input
                    class="form-control"
                    type="text"
                    name="name"
                    value="<?= e($_POST['name'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    class="form-control"
                    type="email"
                    name="email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input
                    class="form-control"
                    type="text"
                    name="phone"
                    value="<?= e($_POST['phone'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    class="form-control"
                    type="password"
                    name="password"
                    required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input
                    class="form-control"
                    type="password"
                    name="confirm_password"
                    required>
            </div>

            <br>

            <button class="btn btn-primary" type="submit">
                Register
            </button>

        </form>

        <br>

        <p>
            Already have an account?
            <a href="login.php">Login Here</a>
        </p>

    </div>

</section>

<?php include __DIR__ . '/../public/partials/footer.php'; ?>