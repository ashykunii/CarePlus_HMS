CREATE DATABASE IF NOT EXISTS `hms_db`;
USE `hms_db`;

-- Admin table
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `updation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin account
INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Specializations table
CREATE TABLE IF NOT EXISTS `specializations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `spec_name` VARCHAR(255) NOT NULL,
  `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default specializations
INSERT INTO `specializations` (`id`, `spec_name`) VALUES
(1, 'Cardiology'),
(2, 'Neurology'),
(3, 'Pediatrics'),
(4, 'Orthopedics'),
(5, 'Dermatology'),
(6, 'General Medicine')
ON DUPLICATE KEY UPDATE `spec_name`=`spec_name`;

-- Doctors table
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `doc_name` VARCHAR(255) NOT NULL,
  `specialization` VARCHAR(255) NOT NULL,
  `contact_no` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default doctor
INSERT INTO `doctors` (`id`, `doc_name`, `specialization`, `contact_no`, `email`, `password`) VALUES
(1, 'Dr. Sarah Jenkins', 'Cardiology', '+1 (555) 234-5678', 'doctor@careplus.com', 'doc123')
ON DUPLICATE KEY UPDATE `email`=`email`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default user
INSERT INTO `users` (`id`, `fullname`, `email`, `password`) VALUES
(1, 'John Doe', 'patient@careplus.com', 'patient123')
ON DUPLICATE KEY UPDATE `email`=`email`;

-- Patients table
CREATE TABLE IF NOT EXISTS `patients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_name` VARCHAR(255) NOT NULL,
  `contact_no` VARCHAR(50) NOT NULL,
  `gender` VARCHAR(20) NOT NULL,
  `age` INT NOT NULL,
  `med_history` TEXT NOT NULL,
  `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default patient
INSERT INTO `patients` (`id`, `patient_name`, `contact_no`, `gender`, `age`, `med_history`) VALUES
(1, 'John Doe', '+1 (555) 987-6543', 'Male', 34, 'Hypertension managed with medication. Annual cardiology checkup routine.')
ON DUPLICATE KEY UPDATE `patient_name`=`patient_name`;

-- Appointments table
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_name` VARCHAR(255) NOT NULL,
  `doc_name` VARCHAR(255) NOT NULL,
  `specialization` VARCHAR(255) NOT NULL,
  `app_date` DATE NOT NULL,
  `app_time` TIME NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Approved',
  `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default appointment
INSERT INTO `appointments` (`id`, `patient_name`, `doc_name`, `specialization`, `app_date`, `app_time`, `status`) VALUES
(1, 'John Doe', 'Dr. Sarah Jenkins', 'Cardiology', CURDATE(), '10:30:00', 'Approved')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Queries table
CREATE TABLE IF NOT EXISTS `queries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `contact_no` VARCHAR(50) NOT NULL,
  `message` TEXT NOT NULL,
  `admin_remark` TEXT NULL,
  `status` VARCHAR(50) DEFAULT 'Unread',
  `reply_date` DATETIME NULL,
  `posting_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctor logs
CREATE TABLE IF NOT EXISTS `doctor_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `doc_email` VARCHAR(255) NOT NULL,
  `login_time` DATETIME NOT NULL,
  `logout_time` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User logs
CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_email` VARCHAR(255) NOT NULL,
  `login_time` DATETIME NOT NULL,
  `logout_time` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
