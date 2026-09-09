# Dental Clinic Appointment Management System

A PHP + MySQL prototype for a public-hospital dental appointment system.

The workflow is nurse-controlled: patients cannot choose or directly change appointment dates. Instead, nurses assign appointments, and patients can submit reschedule requests for nurse review.

## Features

- Nurse login and dashboard
- Patient login and dashboard
- Nurse-assigned appointment scheduling
- Patient reschedule request submission
- Nurse review of reschedule requests
- Demo accounts for quick testing
- Simple setup script for local installation
- PDO-based database access
- Responsive UI with HTML, CSS, and vanilla JavaScript

## Core business rule

Patients **cannot choose or directly change an appointment date**.

### Workflow

1. Main nurse assigns the appointment date and time.
2. Patient logs in and views the appointment.
3. If the patient cannot attend, they submit a rescheduling request.
4. Nurse reviews the request.
5. Nurse decides the new date and time.
6. Patient sees the updated appointment in their dashboard.

## Technology stack

- PHP 8+
- MySQL 8+
- HTML5
- CSS3
- Vanilla JavaScript
- PDO prepared statements

## Project structure

- `config/` — database connection and authentication helpers
- `nurse/` — nurse dashboard, patients, appointments, and request handling
- `patient/` — patient dashboard and rescheduling requests
- `assets/` — CSS and JavaScript assets
- `partials/` — shared layout components
- `database.sql` — database schema and seed data
- `setup.php` — setup script for demo accounts and initial configuration

## Installation

### Requirements

- XAMPP or another PHP + MySQL stack
- PHP 8 or newer
- MySQL 8 or newer
- phpMyAdmin or another MySQL client

### Local setup with XAMPP

1. Install XAMPP and start **Apache** and **MySQL**.
2. Copy the project folder into your web root, for example:
   `C:\xampp\htdocs\public-hospital-dental-appointment-system`
3. Open phpMyAdmin.
4. Import `database.sql`.
5. Open the setup page in your browser:
   `http://localhost/public-hospital-dental-appointment-system/setup.php`
6. After setup, go to the login page:
   `http://localhost/public-hospital-dental-appointment-system/login.php`

## Demo accounts

Use the following credentials to test the system:

- **Nurse**
  - Username: `nurse`
  - Password: `password`

- **Patient**
  - Username: `patient1`
  - Password: `password`

## Usage

### Nurse

- Log in using the nurse account.
- Assign appointments to patients.
- Review rescheduling requests.
- Approve or reject requests and assign a new date/time.

### Patient

- Log in using the patient account.
- View assigned appointment details.
- Submit a rescheduling request if you cannot attend.
- View updated appointment information after nurse review.

## Database schema

The database includes these main tables:

- `users`
- `patients`
- `appointments`
- `reschedule_requests`

## Security notes

This project is a prototype and should not be treated as production-ready.

If you plan to use it in a real public-hospital environment, you should add:

- HTTPS
- CSRF protection
- stricter authorization checks
- audit logging
- secure session handling
- role-based access hardening
- backup and recovery procedures
- privacy/compliance controls
- stronger credential and server configuration policies

**Do not use real patient information without proper authorization and security controls.**

## Setup script warning

After installation, **delete or rename `setup.php`** so it cannot be accessed again.

## Screenshots

<img width="1366" height="599" alt="image" src="https://github.com/user-attachments/assets/30e17e42-4aeb-4f75-aa31-40554c6cb68a" />
<img width="1366" height="599" alt="image" src="https://github.com/user-attachments/assets/c439e719-0f55-4e1a-9a9d-edd6fe07be4e" />
<img width="1338" height="596" alt="image" src="https://github.com/user-attachments/assets/9c56af28-9a14-4a3c-a23f-e6099e68b8e7" />
<img width="1364" height="595" alt="image" src="https://github.com/user-attachments/assets/12e2c979-5e32-4887-ac16-cfd91d430673" />



## License

No license has been specified yet.
