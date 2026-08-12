<?php
require_once __DIR__ . '/config/database.php';

if (is_logged_in()) {
    redirect_to('admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($_POST['password'] ?? '', $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_username'] = $admin['username'];

        redirect_to('admin/index.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>

<body class="auth-bg">
    <div class="login-wrap">

        <div class="login-panel brand-panel">
            <span class="badge">Admin Panel</span>
            <h1><?= e(APP_NAME) ?></h1>
            <p>Normal website + connected admin CRUD in one PHP project.</p>

            <ul class="feature-list">
                <li>Public homepage</li>
                <li>Online appointment booking</li>
                <li>Doctor and patient management</li>
                <li>Billing, pharmacy and lab modules</li>
            </ul>
        </div>

        <div class="login-panel">
            <h2>Login</h2>
            <p class="muted">Use admin username and password.</p>

            <?php if ($error): ?>
                <div class="alert error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" class="form-stack">
                <label>Username</label>
                <input name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button class="btn btn-primary full-btn">Login</button>
            </form>

            <div class="demo-box">
                <strong>Default:</strong>
                <div>admin / admin123</div>
                <div class="small muted">Change from database later.</div>
            </div>

            <p style="margin-top:18px">
                <a class="btn btn-secondary" href="<?= e(url('index.php')) ?>">
                    Back to Website
                </a>
            </p>
        </div>

    </div>
</body>
</html>