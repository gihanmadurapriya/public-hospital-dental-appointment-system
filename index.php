<!-- <?php
session_start();
if (isset($_SESSION['role'])) {
    header('Location: ' . ($_SESSION['role'] === 'NURSE' ? 'nurse/dashboard.php' : 'patient/dashboard.php'));
} else {
    header('Location: login.php');
}
exit;
?> -->