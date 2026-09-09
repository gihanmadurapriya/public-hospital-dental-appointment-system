<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        header('Location: ' . ($user['role'] === 'NURSE' ? 'nurse/dashboard.php' : 'patient/dashboard.php'));
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dental Clinic Login</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="login-page">
<div class="login-card">
<div class="brand">🦷 Dental Clinic</div>
<h1>Sign in</h1>
<p class="muted">Appointment Management System</p>
<?php if ($error): ?><div class="alert error"><?=h($error)?></div><?php endif; ?>
<form method="post">
<label>Username<input name="username" required autofocus></label>
<label>Password<input type="password" name="password" required></label>
<button class="btn full">Login</button>
</form>
<div class="demo"><strong>Demo accounts</strong><br>Nurse: nurse / password<br>Patient: patient1 / password</div>
</div></body></html>