# Dental Clinic Appointment Management System

A PHP + MySQL prototype for a public-hospital dental clinic.

## Core rule

Patients **cannot choose or directly change an appointment date**.

The workflow is:

1. Main nurse assigns the appointment date/time.
2. Patient logs in and views the appointment.
3. If the patient cannot attend, they submit a rescheduling request.
4. Nurse reviews the request.
5. Nurse decides the new date/time.
6. Patient sees the new appointment in their dashboard.

## Technology

- PHP 8+
- MySQL 8+
- HTML5
- CSS3
- Vanilla JavaScript
- PDO prepared statements

## Installation with XAMPP

1. Install XAMPP and start **Apache** and **MySQL**.
2. Copy the `dental_clinic_system` folder into:
   `C:\xampp\htdocs\`
3. Open phpMyAdmin.
4. Import `database.sql`.
5. Open:
   `http://localhost/dental_clinic_system/setup.php`
6. Login at:
   `http://localhost/dental_clinic_system/login.php`

Demo accounts:

- Nurse: `nurse` / `password`
- Patient: `patient1` / `password`

After setup, delete or rename `setup.php`.

## Important production notes

This is an academic/prototype system. A real public-hospital deployment needs additional security and operational controls, including HTTPS, CSRF protection, stricter authorization, audit logs, backups, secure password/credential management, session hardening, privacy controls, server configuration, and hospital-specific policies.

Do not use real patient information in this prototype without proper authorization and security controls.

## Main folders

- `config/` — database and authentication
- `nurse/` — nurse dashboard, patients, appointments and request handling
- `patient/` — patient dashboard and rescheduling request
- `assets/` — CSS and JavaScript
- `partials/` — common layout
