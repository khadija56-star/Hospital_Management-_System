CREATE DATABASE IF NOT EXISTS hospital_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_management;

DROP TABLE IF EXISTS admin_users;
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL
);
INSERT INTO admin_users(full_name, username, password, created_at) VALUES
('System Admin', 'admin', '$2y$12$5uE.ctDY6yYIToO2EawwUeY1HX6Ai4eVvpEYbLikjiaTe1gJ9k91q', NOW());

DROP TABLE IF EXISTS patients;
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  age INT DEFAULT 0,
  gender VARCHAR(20) DEFAULT 'Male',
  phone VARCHAR(30) NOT NULL,
  blood_group VARCHAR(10) DEFAULT '',
  address VARCHAR(255) DEFAULT '',
  created_at DATETIME NOT NULL
);
INSERT INTO patients(name,age,gender,phone,blood_group,address,created_at) VALUES
('Rahim Uddin',35,'Male','01710000001','A+','Dhaka',NOW()),
('Nusrat Jahan',28,'Female','01710000002','B+','Chattogram',NOW());

DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  specialization VARCHAR(120) NOT NULL,
  phone VARCHAR(30) DEFAULT '',
  email VARCHAR(120) DEFAULT '',
  schedule VARCHAR(180) DEFAULT '',
  created_at DATETIME NOT NULL
);
INSERT INTO doctors(name,specialization,phone,email,schedule,created_at) VALUES
('Dr. Hasan Mahmud','Cardiology','01810000001','hasan@greenlifehospital.com','Sat-Thu 10AM-2PM',NOW()),
('Dr. Sabiha Noor','Internal Medicine','01810000002','sabiha@greenlifehospital.com','Sat-Thu 4PM-8PM',NOW()),
('Dr. Imran Kabir','Orthopedic Surgery','01810000003','imran@greenlifehospital.com','Sun-Thu 11AM-3PM',NOW()),
('Dr. Farzana Islam','Gynecology & Obstetrics','01810000004','farzana@greenlifehospital.com','Sat-Wed 5PM-9PM',NOW()),
('Dr. Mahmudul Karim','Neurology','01810000005','mahmudul@greenlifehospital.com','Sun-Thu 9AM-1PM',NOW()),
('Dr. Nusrat Jahan','Pediatrics','01810000006','nusrat@greenlifehospital.com','Sat-Thu 3PM-7PM',NOW()),
('Dr. Rezaul Haque','General Surgery','01810000007','rezaul@greenlifehospital.com','Sat-Wed 10AM-1PM',NOW()),
('Dr. Tania Rahman','Dermatology','01810000008','tania@greenlifehospital.com','Sun-Thu 5PM-8PM',NOW()),
('Dr. Shakil Ahmed','ENT','01810000009','shakil@greenlifehospital.com','Sat-Thu 12PM-4PM',NOW()),
('Dr. Ayesha Siddika','Ophthalmology','01810000010','ayesha@greenlifehospital.com','Sun-Thu 9AM-12PM',NOW()),
('Dr. Kamrul Islam','Urology','01810000011','kamrul@greenlifehospital.com','Sat-Wed 6PM-9PM',NOW()),
('Dr. Rukhsana Parvin','Endocrinology','01810000012','rukhsana@greenlifehospital.com','Sun-Thu 2PM-6PM',NOW());

DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  appointment_date DATETIME NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Scheduled',
  notes TEXT,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_appointments_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_appointments_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);
INSERT INTO appointments(patient_id,doctor_id,appointment_date,status,notes,created_at) VALUES
(1,1,NOW(),'Scheduled','Initial cardiac consultation',NOW()),
(2,2,DATE_ADD(NOW(), INTERVAL 1 DAY),'Completed','Follow-up visit',NOW());

DROP TABLE IF EXISTS billing;
CREATE TABLE billing (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  bill_no VARCHAR(50) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  billing_date DATE NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_billing_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
INSERT INTO billing(patient_id,bill_no,amount,payment_status,billing_date,created_at) VALUES
(1,'INV-1001',1500.00,'Paid',CURDATE(),NOW()),
(2,'INV-1002',2200.00,'Pending',CURDATE(),NOW());

DROP TABLE IF EXISTS pharmacy_items;
CREATE TABLE pharmacy_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_name VARCHAR(120) NOT NULL,
  category VARCHAR(80) DEFAULT '',
  quantity INT NOT NULL DEFAULT 0,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  expiry_date DATE DEFAULT NULL,
  created_at DATETIME NOT NULL
);
INSERT INTO pharmacy_items(item_name,category,quantity,unit_price,expiry_date,created_at) VALUES
('Napa','Tablet',150,2.50,DATE_ADD(CURDATE(), INTERVAL 12 MONTH),NOW()),
('Seclo','Capsule',80,7.00,DATE_ADD(CURDATE(), INTERVAL 10 MONTH),NOW());

DROP TABLE IF EXISTS lab_tests;
CREATE TABLE lab_tests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  test_name VARCHAR(120) NOT NULL,
  result_summary TEXT,
  status VARCHAR(30) NOT NULL DEFAULT 'Pending',
  test_date DATE NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_lab_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
INSERT INTO lab_tests(patient_id,test_name,result_summary,status,test_date,created_at) VALUES
(1,'CBC','Normal range', 'Completed', CURDATE(), NOW()),
(2,'Blood Sugar','Pending analysis', 'Pending', CURDATE(), NOW());
