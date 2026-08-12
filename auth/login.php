<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Login";

$error = "";

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email == '' || $password == '') {

        $error = "Please enter email and password.";

    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: ../appointment.php");
            exit;

        } else {

            $error = "Invalid email or password.";

        }

    }

}

include __DIR__ . '/../public/partials/header.php';
?>

<section class="section">

<div class="container" style="max-width:500px;">

<h2>Login</h2>

<?php if(!empty($success)): ?>
<div class="alert success">
<?= e($success) ?>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert error">
<?= e($error) ?>
</div>
<?php endif; ?>

<form method="post" class="appointment-form">

<div class="form-group">
<label>Email</label>
<input
class="form-control"
type="email"
name="email"
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

<br>

<button class="btn btn-primary" type="submit">
Login
</button>

</form>

<br>

<p>
Don't have an account?
<a href="register.php">Register Here</a>
</p>

</div>

</section>

<?php include __DIR__ . '/../public/partials/footer.php'; ?>