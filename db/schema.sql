-- Schema for blood_donation_db
CREATE DATABASE IF NOT EXISTS blood_donation_db;
USE blood_donation_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(30),
  gender ENUM('Male','Female','Other') DEFAULT 'Other',
  blood_type VARCHAR(3),
  address TEXT,
  user_type ENUM('Admin','Staff','Donor','Recipient') NOT NULL DEFAULT 'Donor',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS donations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT NOT NULL,
  blood_type VARCHAR(3) NOT NULL,
  quantity_ml INT NOT NULL,
  status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
  date_donated DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT NOT NULL,
  appointment_date DATETIME NOT NULL,
  location VARCHAR(255),
  status ENUM('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipient_id INT NOT NULL,
  blood_type VARCHAR(3) NOT NULL,
  quantity_ml INT NOT NULL,
  status ENUM('Pending','Approved','Rejected','Fulfilled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fulfilled_by_donation_id INT,
  FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (fulfilled_by_donation_id) REFERENCES donations(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS blood_inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  blood_type VARCHAR(3) NOT NULL,
  quantity_ml INT NOT NULL DEFAULT 0,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  subject VARCHAR(255),
  message TEXT,
  date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admins_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  action VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed: create an admin user (password: admin123)
INSERT INTO users (full_name, email, password, user_type)
SELECT 'Admin User', 'admin@example.com', '$2y$10$SY9lfx3BRnZ8pW8cdDjS5ugpu9tz7N.gYJMeKlv8GeP7VDEyJTQUW', 'Admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='admin@example.com');

-- Seed: initial inventory rows (if not exists)
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'A+', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='A+');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'A-', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='A-');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'B+', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='B+');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'B-', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='B-');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'AB+', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='AB+');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'AB-', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='AB-');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'O+', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='O+');
INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT 'O-', 0 WHERE NOT EXISTS (SELECT 1 FROM blood_inventory WHERE blood_type='O-');
