<?php
declare(strict_types=1);
session_start();

function require_login(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /dental_clinic_system/login.php');
        exit;
    }
}

function require_role(string $role): void {
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
