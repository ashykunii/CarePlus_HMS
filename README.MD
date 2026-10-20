# CarePlus Hospital Management System

A web-based Hospital Management System built with PHP, MySQL, HTML5, CSS3, and JavaScript to manage patients, doctors, appointments, and medical records.

![Language](https://img.shields.io/badge/PHP-7.4%2F8.x-blue)
![Database](https://img.shields.io/badge/Database-MySQL-lightgrey)
![Server](https://img.shields.io/badge/Server-Apache%20%2F%20XAMPP-green)
![License](https://img.shields.io/badge/License-Educational%20Use-orange)
![Status](https://img.shields.io/badge/Status-Academic%20Project-yellow)

<!-- Optional: add a banner, logo, or demo GIF here -->
<!-- ![Demo](docs/demo.gif) -->

---

## Table of Contents

- [About the Project](#about-the-project)
- [Objectives](#objectives)
- [Key Features](#key-features)
- [Screenshots](#screenshots)
- [Architecture Overview](#architecture-overview)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Design](#database-design)
- [Demo Accounts](#demo-accounts)
- [Troubleshooting](#troubleshooting)
- [Security Improvements for Production](#security-improvements-for-production)
- [Roadmap](#roadmap)
- [Academic Credits](#academic-credits)
- [License](#license)

---

## About the Project

**CarePlus HMS** streamlines hospital operations by bringing patient care and administrative tasks into one platform. It provides separate role-based interfaces for **patients**, **doctors**, and **administrators**, all backed by a structured MySQL database.

The system is built with plain PHP and a shared set of includes (header, footer, sidebars, helpers), and runs on a standard Apache/MySQL stack such as XAMPP.

## Objectives

- Digitize basic hospital management processes.
- Provide patients with an online appointment system.
- Allow doctors to manage patient clinical information.
- Give administrators centralized control over hospital operations.
- Store hospital information in a structured MySQL database.
- Reduce appointment scheduling conflicts.
- Provide a simple, modern, and responsive user interface.
- Practice real-world web development using PHP and MySQL.

## Key Features

### Patients
- Register, log in, and log out securely.
- Update profile details and change password.
- Book appointments by choosing a specialization, doctor, date, and time slot.
- View appointment history with live status.
- Access medical records added by their doctors.

### Doctors
- Dedicated portal with a dashboard of upcoming appointments and daily stats.
- View appointment history and daily schedule.
- Manage patient clinical records (prescriptions, diagnoses, notes).
- View patient profiles, manage their own profile, and change password.

### Administrators
- Control dashboard for the whole system.
- Manage doctors, specializations, patients, and user accounts.
- Manage appointment schedules and view complete appointment histories.
- View patient records across the hospital.
- Monitor doctor and user login activity (`doctor_logs`, `user_logs`).
- View and respond to contact messages and public queries.

### Appointment Booking
- Specialization, doctor, date, and time slot selection.
- Live status tracking: *Pending*, *Approved*, *Cancelled*.
- First-come, first-served logic.
- Doctor time-slot and patient schedule conflict detection, so double-booking is prevented.

## Screenshots

<!-- Store images in a /docs or /screenshots folder -->

| Home Page | Patient Dashboard |
| :-------: | :---------------: |
| ![Home page](docs/home.png) | ![Patient dashboard](docs/patient-dashboard.png) |

| Book Appointment | Doctor Dashboard |
| :--------------: | :--------------: |
| ![Book appointment](docs/book-appointment.png) | ![Doctor dashboard](docs/doctor-dashboard.png) |

## Architecture Overview

The app uses a role-based, page-per-feature structure. Each role has its own folder of PHP pages, which share common layout includes and a single database connection file.

```
┌──────────────────────────────┐
│  Browser (HTML/CSS/JS)       │  (Forms, dashboards, client-side validation)
└──────────────┬───────────────┘
               ▼
┌──────────────────────────────┐
│  PHP Pages by Role           │  (admin/, doctor/, patient/)
└──────────────┬───────────────┘
               ▼
┌──────────────────────────────┐
│  Shared Includes & Config    │  (includes/, config/db.php, helpers.php)
└──────────────┬───────────────┘
               ▼
┌──────────────────────────────┐
│  MySQL Database (hms_db)     │  (Users, doctors, appointments, logs)
└──────────────────────────────┘
```

| Layer / Component | Responsibility |
| ----------------- | -------------- |
| **Role folders**  | Pages and logic for each user type (`admin/`, `doctor/`, `patient/`) |
| **includes/**     | Shared header, footer, role sidebars, and helper functions |
| **config/**       | Database connection settings |
| **assets/**       | Shared stylesheet and JavaScript |

## Tech Stack

| Category | Technology | Purpose |
| -------- | ---------- | ------- |
| Backend  | PHP        | Server-side logic and routing |
| Database | MySQL      | Relational data storage |
| Frontend | HTML5      | Semantic page structure |
| Frontend | CSS3       | Custom design system and responsive styling |
| Frontend | JavaScript | Client-side validation and interactive UI |
| Server   | Apache     | Local HTTP web server |
| Tools    | XAMPP      | Integrated local development environment |

## Project Structure

```
HospitalManagement_System/
├── admin/
│   ├── appointment-history.php
│   ├── change-password.php
│   ├── dashboard.php
│   ├── doctor-log.php
│   ├── doctor-spec.php
│   ├── login.php
│   ├── logout.php
│   ├── manage-doctor.php
│   ├── manage-user.php
│   ├── query.php
│   ├── schedule.php
│   └── view-patient.php
├── assets/
│   ├── main.js
│   └── style.css
├── config/
│   └── db.php
├── Database/
│   └── hms_db.sql
├── doctor/
│   ├── appointment-history.php
│   ├── change-password.php
│   ├── dashboard.php
│   ├── forget-password.php
│   ├── login.php
│   ├── logout.php
│   ├── manage-patient.php
│   ├── profile.php
│   └── schedule.php
├── includes/
│   ├── admin_sidebar.php
│   ├── doctor_sidebar.php
│   ├── footer.php
│   ├── header.php
│   ├── helpers.php
│   └── patient_sidebar.php
├── patient/
│   ├── appointment-history.php
│   ├── book-appointment.php
│   ├── change-password.php
│   ├── dashboard.php
│   ├── forgot-password.php
│   ├── login.php
│   ├── logout.php
│   ├── medical-record.php
│   ├── profile.php
│   └── register.php
├── contact.php
├── index.php
└── README.md
```

## Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or another stack with Apache, PHP 7.4 or 8.x, MySQL/MariaDB, and phpMyAdmin)
- A modern browser

### Setup

1. **Copy the project** into your XAMPP `htdocs` directory:
```text
   C:\xampp\htdocs\HospitalManagement_System
```

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Import the database**
   1. Open [phpMyAdmin](http://localhost/phpmyadmin).
   2. Create a database named `hms_db`.
   3. Import `Database/hms_db.sql`. The script creates all tables and sample data.

4. **Check the database connection** in `config/db.php` (see [Configuration](#configuration)).

5. **Open the app** at `http://localhost/HospitalManagement_System/`.

## Configuration

Database settings are in `config/db.php`. Change them if your local setup differs.

```php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "hms_db";
```

| Variable   | Default     | Description |
| ---------- | ----------- | ----------- |
| `$db_host` | `localhost` | Database server host |
| `$db_user` | `root`      | Database username |
| `$db_pass` | *(empty)*   | Database password |
| `$db_name` | `hms_db`    | Database name |

> ⚠️ Never use the default empty `root` password outside local development.

## Usage

### Patient

```text
Register → Login → Dashboard → Select Specialization → Select Doctor
→ Select Date & Time → Check Availability → Submit Appointment
→ Wait for Approval → View Status & History
```

### Doctor

```text
Login → Dashboard → View Schedule → View Appointments
→ Manage Patient Records → Update Clinical Notes & Details
```

### Administrator

```text
Login → Dashboard → Manage Doctors & Specializations → Manage Patients & Users
→ Manage Schedules & Appointments → Review Activity Logs
```

## Database Design

The system uses a MySQL database named **`hms_db`** with these tables:

| Table             | Purpose |
| ----------------- | ------- |
| `admin`           | Administrator credentials |
| `doctors`         | Doctor profiles, credentials, fees, and specializations |
| `users` / `patients` | Patient registration details and demographics |
| `specializations` | Medical departments and fields |
| `appointments`    | Bookings, dates, times, and statuses |
| `queries`         | Contact messages submitted from the public portal |
| `doctor_logs`     | Doctor sign-in audit logs and session IP addresses |
| `user_logs`       | Patient sign-in audit logs |

## Demo Accounts

> ⚠️ For local development and testing only. Change or delete these before any production deployment.

| Role          | Username / Email | Password         |
| ------------- | ---------------- | ---------------- |
| Administrator | `Admin`          | `adminpassword`  |
| Doctor        | `[doctor email]` | `doctorpassword` |
| Patient       | `[patient email]`| `userpassword`   |

## Troubleshooting

<details>
<summary><strong>Apache or MySQL will not start in XAMPP</strong></summary>

Another program is probably using port 80 or 3306 (for example Skype, IIS, or another MySQL service). Stop that program, or change the port in the XAMPP Control Panel under **Config**.
</details>

<details>
<summary><strong>"Database connection failed" error</strong></summary>

Make sure MySQL is running, the `hms_db` database exists with the imported tables, and the values in `config/db.php` match your setup.
</details>

<details>
<summary><strong>404 Not Found when opening the app</strong></summary>

Confirm the folder is named `HospitalManagement_System` and sits directly inside `htdocs`, and that the URL matches the folder name exactly.
</details>

<details>
<summary><strong>Old styles or scripts still showing</strong></summary>

Do a hard refresh with `Ctrl + Shift + R` (Windows/Linux) or `Cmd + Shift + R` (macOS).
</details>

## Security Improvements for Production

This project was built as an educational demonstration. Before using it with real patient data, implement the following:

- **Prepared statements**: Move remaining queries to PDO or MySQLi prepared statements to prevent SQL injection.
- **Strict RBAC**: Enforce granular permission checks on every backend endpoint.
- **CSRF tokens**: Validate anti-CSRF tokens on all POST forms.
- **Secure password reset**: Replace the basic flow with time-limited, token-based email verification.
- **Secure sessions**: Enable `HttpOnly`, `SameSite=Strict`, and `Secure` cookie flags.
- **HTTPS**: Force TLS/SSL on all routes.
- **Data encryption**: Encrypt sensitive personal information at rest.
- **Audit logging**: Keep comprehensive, tamper-resistant access logs.
- **Input sanitization**: Strictly validate and sanitize all user input.
- **Credential hardening**: Remove default accounts and enforce strong password policies.

## Roadmap

- [x] Role-based portals for patients, doctors, and administrators
- [x] Online appointment booking with conflict detection
- [x] Doctor-managed medical records
- [x] Login activity logs for doctors and users
- [ ] Appointment duration input, to prevent overlapping or very short appointments *(lecturer feedback)*
- [ ] Medical prescription inputs in medical records, separate from general notes *(lecturer feedback)*
- [ ] Email notifications for appointment confirmations
- [ ] SMS reminders
- [ ] Interactive doctor availability calendar
- [ ] Digital prescription generation
- [ ] Medical document and lab result uploads
- [ ] Advanced patient search and filtering
- [ ] Hospital analytics and financial reports
- [ ] Online payment gateway
- [ ] Mobile companion app
- [ ] Multi-hospital branch support

## Academic Credits

* **Project Name:** CarePlus Hospital Management System
* **Repository Source:** [GitHub Repository Link](https://github.com/ashykunii/CarePlus_HMS)
* **Author / Developer:** Sok Sorya
* **Course Title:** Web Application Development with PHP and MySQL
* **Supervising Lecturer:** Mork Ratha
* **License:** This project and its underlying code structure are developed and licensed exclusively for **educational and personal use only**.

## License

This project is developed and licensed exclusively for **educational and personal use only**.

<p align="center">© 2026 Sok Sorya. All Rights Reserved.</p>