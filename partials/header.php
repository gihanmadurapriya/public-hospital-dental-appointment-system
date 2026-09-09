<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($title ?? 'Dental Clinic')?></title><link rel="stylesheet" href="/dental_clinic_system/assets/css/style.css"></head>
<body>
<header class="topbar"><div class="brand">🦷 Dental Clinic</div><div><span class="user"><?=h($_SESSION['full_name'])?></span><a class="btn small secondary" href="/dental_clinic_system/logout.php">Logout</a></div></header>
<div class="layout">
<aside class="sidebar">
<a href="/dental_clinic_system/<?= $_SESSION['role']==='NURSE' ? 'nurse/dashboard.php' : 'patient/dashboard.php' ?>">Dashboard</a>
<?php if ($_SESSION['role']==='NURSE'): ?>
<a href="/dental_clinic_system/nurse/appointments.php">Appointments</a>
<a href="/dental_clinic_system/nurse/patients.php">Patients</a>
<a href="/dental_clinic_system/nurse/requests.php">Reschedule Requests</a>
<?php else: ?>
<a href="/dental_clinic_system/patient/dashboard.php">My Appointment</a>
<?php endif; ?>
</aside><main class="main">
