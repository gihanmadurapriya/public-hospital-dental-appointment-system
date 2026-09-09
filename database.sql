CREATE DATABASE IF NOT EXISTS dental_clinic
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dental_clinic;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('NURSE','PATIENT') NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    phone VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    patient_number VARCHAR(30) NOT NULL UNIQUE,
    date_of_birth DATE NULL,
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    treatment VARCHAR(150) NOT NULL,
    notes TEXT,
    status ENUM('SCHEDULED','COMPLETED','ABSENT','CANCELLED','RESCHEDULED') NOT NULL DEFAULT 'SCHEDULED',
    assigned_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_patient_date (patient_id, appointment_date)
);

CREATE TABLE reschedule_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT UNSIGNED NOT NULL,
    patient_id INT UNSIGNED NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
    nurse_note TEXT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    handled_at TIMESTAMP NULL,
    handled_by INT UNSIGNED NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (handled_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_request_status (status)
);

-- Demo nurse account
-- Password: password
INSERT INTO users (username, password_hash, role, full_name, phone)
VALUES ('nurse', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaJ9oYbJ3Qe3Y3xXx7fLQYQ8V3K', 'NURSE', 'Main Dental Nurse', '0112345678');

-- Demo patient account
-- Password: password
INSERT INTO users (username, password_hash, role, full_name, phone)
VALUES ('patient1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaJ9oYbJ3Qe3Y3xXx7fLQYQ8V3K', 'PATIENT', 'Demo Patient', '0771234567');

INSERT INTO patients (user_id, patient_number, date_of_birth, address)
SELECT id, 'P0001', '2000-01-15', 'Colombo'
FROM users WHERE username='patient1';
