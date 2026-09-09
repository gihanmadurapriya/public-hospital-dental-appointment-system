<?php
require_once __DIR__ . '/config/database.php';

function upsertUser(PDO $pdo, string $username, string $password, string $role, string $name, string $phone): int {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
    $stmt->execute([$username]);
    $id = $stmt->fetchColumn();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($id) {
        $stmt = $pdo->prepare("UPDATE users SET password_hash=?, role=?, full_name=?, phone=? WHERE id=?");
        $stmt->execute([$hash, $role, $name, $phone, $id]);
        return (int)$id;
    }
    $stmt = $pdo->prepare("INSERT INTO users(username,password_hash,role,full_name,phone) VALUES(?,?,?,?,?)");
    $stmt->execute([$username,$hash,$role,$name,$phone]);
    return (int)$pdo->lastInsertId();
}

$nurseId = upsertUser($pdo, 'nurse', 'password', 'NURSE', 'Main Dental Nurse', '0112345678');
$patientUserId = upsertUser($pdo, 'patient1', 'password', 'PATIENT', 'Demo Patient', '0771234567');

$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id=?");
$stmt->execute([$patientUserId]);
$patientId = $stmt->fetchColumn();
if (!$patientId) {
}
$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id=?");
$stmt->execute([$patientUserId]);
if (!$stmt->fetchColumn()) {
    $stmt = $pdo->prepare("INSERT INTO patients(user_id,patient_number,date_of_birth,address) VALUES(?,?,?,?)");
    $stmt->execute([$patientUserId,'P0001','2000-01-15','Colombo']);
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Setup complete</title>
<link rel="stylesheet" href="assets/css/style.css"></head>
<body class="center"><div class="card">
<h1>Dental Clinic System Ready</h1>
<p>Database tables and demo accounts are ready.</p>
<p><strong>Nurse:</strong> nurse / password</p>
<p><strong>Patient:</strong> patient1 / password</p>
<a class="btn" href="login.php">Go to Login</a>
<p class="muted">For security, delete or rename setup.php after installation.</p>
</div></body></html>