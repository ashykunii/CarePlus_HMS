# CarePlus Hospital Management System

A web-based Hospital Management System built with **PHP, MySQL, HTML5, CSS3, and JavaScript.**

**CarePlus HMS** is a modern healthcare platform designed to streamline hospital operations, enhance patient care, and improve administrative efficiency. It provides a centralized system for **managing patients, doctors, appointments, medical records, schedules, and administrative operations.**

---

## Table of Contents

- [About the Project](#about-the-project)
- [Key Objectives](#key-objectives)
- [Features](#features)
  - [Patient Management](#patient-management)
  - [Doctor Management](#doctor-management)
  - [Administrator Management](#administrator-management)
  - [Appointment Management](#appointment-management)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Database Overview](#database-overview)
- [Installation & Setup](#installation--setup)
- [Default Accounts](#default-accounts)
- [User Workflows](#user-workflows)
- [Development Challenges](#development-challenges)
- [Key Benefits](#key-benefits)
- [Future Improvements](#future-improvements)
- [Security Improvements for Production](#security-improvements-for-production)
- [Academic Credits & License](#academic-credits--license)

---

## About the Project

**CarePlus Hospital Management System** is designed to simplify hospital operations by bringing different healthcare management tasks into one unified digital platform.

The system provides separate role-based interfaces for:
- **Patients**
- **Doctors**
- **Administrators**

### Key Objectives

- **Digitize basic hospital management processes.**
- **Provide patients with an online appointment system.**
- **Allow doctors to manage patient clinical information.**
- **Give administrators centralized control over hospital operations.**
- **Store hospital information in a structured MySQL database.**
- **Reduce appointment scheduling conflicts.**
- **Provide a simple, modern, and responsive user interface.**
- **Practice real-world web development using PHP and MySQL.**

---

## Features

### Patient Management
Patients can:
- **Create an account** and register online.
- **Log in and log out** securely.
- **Update profile details** (Contact info, address, gender).
- **Change their password**.
- **Book appointments** seamlessly online.
- **Select medical specializations** and preferred doctors.
- **Select appointment dates and time slots**.
- **View appointment history** and live statuses.
- **Access personal medical records** added by attending doctors.

### Doctor Management
Doctors can:
- **Log in** to their dedicated portal.
- **View their dashboard** with upcoming appointments and daily stats.
- **View appointment history** for all assigned patients.
- **Manage patient clinical records** (Prescriptions, diagnoses, notes).
- **View detailed patient profiles**.
- **Manage profile information**.
- **View their daily appointment schedule**.
- **Change their password**.
- **Log out securely**.

### Administrator Management
Administrators can:
- **Access the administrative control dashboard**.
- **Manage doctors** (Add, edit, remove doctors and specializations).
- **Manage patients and user accounts**.
- **Manage medical specializations**.
- **Manage appointment schedules** and system logs.
- **View patient records** across the hospital network.
- **View complete appointment histories**.
- **Monitor doctor login activity** (`doctor_logs`).
- **Monitor user login activity** (`user_logs`).
- **Manage administrator credentials**.
- **View and respond to contact messages and public queries**.

### Appointment Management
The intelligent booking module includes:
- **Medical specialization selection**.
- **Doctor selection**.
- **Date and time slot selection**.
- **Live appointment status tracking** (*Pending*, *Approved*, *Cancelled*).
- **First-come, first-served booking logic**.
- **Doctor time-slot conflict detection**.
- **Patient schedule conflict detection**.
- **Complete appointment history tracking**.

> **Note:** The system automatically checks existing doctor and patient schedules before confirming a new booking to prevent double-booking.

---

## Tech Stack

| Technology | Purpose |
| :--- | :--- |
| **PHP** | Backend server-side logic & routing |
| **MySQL** | Relational database management |
| **HTML5** | Semantic web page structure |
| **CSS3** | Custom design system & responsive styling |
| **JavaScript** | Client-side validation & interactive UI |
| **Apache** | Local HTTP web server |
| **XAMPP** | Integrated development environment |

---

## Project Structure

```text
HospitalManagement_System/
│
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
│
├── assets/
│   ├── main.js
│   └── style.css
│
├── config/
│   └── db.php
│
├── Database/
│   └── hms_db.sql
│
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
│
├── includes/
│   ├── admin_sidebar.php
│   ├── doctor_sidebar.php
│   ├── footer.php
│   ├── header.php
│   ├── helpers.php
│   └── patient_sidebar.php
│
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
│
├── contact.php
├── index.php
└── README.MD
```

---

## Database Overview

The system runs on a relational MySQL database named **`hms_db`**.

It contains structured tables for:
- **`admin`** — System administrative credentials.
- **`doctors`** — Doctor profiles, credentials, fees, and specializations.
- **`users` / `patients`** — Patient registration details and demographics.
- **`specializations`** — Medical departments and fields.
- **`appointments`** — Appointment bookings, dates, times, and statuses.
- **`queries`** — Public contact messages submitted from the portal.
- **`doctor_logs`** — Audit logs of doctor sign-ins and session IP addresses.
- **`user_logs`** — Audit logs of user/patient sign-in activity.

---

## Installation & Setup

### 1. Install XAMPP
Install [XAMPP](https://www.apachefriends.org/) or another stack providing:
- Apache Web Server
- PHP (7.4 or 8.x)
- MySQL / MariaDB Database Server
- phpMyAdmin

### 2. Copy the Project Directory
Copy the project folder into your local XAMPP `htdocs` directory:
```text
C:\xampp\htdocs\HospitalManagement_System
```

### 3. Start XAMPP Control Panel
Open XAMPP Control Panel and start both **Apache** and **MySQL**.

### 4. Import the Database
1. Open phpMyAdmin in your browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Create or select database **`hms_db`** (or import directly).
3. Import `Database/hms_db.sql` file.
   *The SQL script automatically creates all required tables and default sample data.*

### 5. Verify Database Connection
Check the configuration file located at `config/db.php`:
```php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "hms_db";
```
*Modify host, username, or password if your local setup differs.*

### 6. Launch Application
Open your browser and navigate to:
```text
http://localhost/HospitalManagement_System/
```

---

## Default Accounts

For development and testing, use the following sample credentials:

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `Admin` | `adminpassword` |
| **Doctor** | `` | `doctorpassword` |
| **Patient** | `` | `userpassword` |

> **Important:** These default accounts are provided exclusively for local testing and demonstration purposes. Change or delete them prior to any production deployment.

---

## User Workflows

### Patient Workflow
```text
Register
   ↓
Login
   ↓
Patient Dashboard
   ↓
Select Specialization
   ↓
Select Doctor
   ↓
Select Date & Time
   ↓
Check Availability
   ↓
Submit Appointment
   ↓
Wait for Approval / Confirmation
   ↓
View Appointment Status & History
```

### Doctor Workflow
```text
Doctor Login
   ↓
Doctor Dashboard
   ↓
View Schedule & Calendar
   ↓
View Patient Appointments
   ↓
Manage Patient Records
   ↓
Update Clinical Notes & Medical Details
```

### Administrator Workflow
```text
Admin Login
   ↓
Admin Dashboard
   ↓
Manage Doctors & Specializations
   ↓
Manage Patients & Users
   ↓
Manage Schedules & Appointments
   ↓
Review System Activity Logs
```

## Future Improvements

Potential enhancements for upcoming versions:
-  **Email notifications** for appointment confirmations.
-  **SMS reminders** prior to scheduled consultations.
-  **Interactive doctor availability calendar**.
-  **Digital prescription generation**.
-  **Medical document & laboratory result file uploads**.
-  **Advanced patient search & filtering options**.
-  **Hospital analytics and financial reports**.
-  **Integrated online payment gateway**.
-  **Mobile application companion**.
-  **Multi-hospital branch support**.

---

## Security Improvements for Production

This software was developed primarily as an educational and demonstration project. Before deploying to a live production environment with real electronic health records (EHR), implement these security measures:

- **Prepared Statements**: Transition remaining queries to PDO or MySQLi prepared statements to eliminate SQL injection risks.
- **Strict Role-Based Access Control (RBAC)**: Enforce granular permission checks on all backend handler endpoints.
- **CSRF Tokens**: Implement anti-CSRF token validation across all POST forms.
- **Secure Password Reset**: Replace basic reset flows with token-based time-sensitive email verification.
- **Secure Session Management**: Enable `HttpOnly`, `SameSite=Strict`, and `Secure` flags on session cookies.
- **HTTPS Enforcement**: Force TLS/SSL encryption across all routes.
- **Data Encryption**: Encrypt sensitive patient personal identifiable information (PII) at rest.
- **Audit Logging**: Maintain comprehensive, non-repudiable system access logs.
- **Input Sanitization**: Enforce strict sanitization and validation on user inputs.
- **Credential Hardening**: Remove all default test accounts and enforce strong password policies.

---

## Additional Notes from Lecturer

- For the booking an appointment, the system should include a duration input for the appointment, to keep the system more efficient from having overlap appointment that is a minute or less or more. 
- For medical record, it should be include the inputs for medical prescription, rather then just the medical record.

## Academic Credits & License

- **Project Name:** CarePlus Hospital Management System
- **Repository Source:** [GitHub Repository Link](#)
- **Authors / Developers:** Sok Sorya
- **Course Title:** Web Application Development with PHP and MySQL
- **Supervising Lecturer:** Mork Ratha
- **License:** This project and its underlying code structure are developed and licensed exclusively for **educational and personal use only**.
