-- Demo data for blood_donation_db
-- Safe to run more than once: every demo record is inserted only when absent.
USE blood_donation_db;

START TRANSACTION;

-- Upgrade databases created before the Staff role was introduced.
ALTER TABLE users
  MODIFY user_type ENUM('Admin','Staff','Donor','Recipient') NOT NULL DEFAULT 'Donor';

-- Demo passwords:
-- admin@example.com     / admin123
-- staff@example.com     / staff123
-- donor@example.com     / donor123
-- donor.two@example.com / donor123
-- recipient@example.com / recipient123

INSERT INTO users (full_name, email, password, phone, gender, blood_type, address, user_type)
SELECT 'System Admin', 'admin@example.com', '$2y$10$SY9lfx3BRnZ8pW8cdDjS5ugpu9tz7N.gYJMeKlv8GeP7VDEyJTQUW', '011 555 0100', 'Other', 'O+', 'Johannesburg', 'Admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@example.com');

UPDATE users
SET password = '$2y$10$SY9lfx3BRnZ8pW8cdDjS5ugpu9tz7N.gYJMeKlv8GeP7VDEyJTQUW',
    user_type = 'Admin'
WHERE email = 'admin@example.com';

INSERT INTO users (full_name, email, password, phone, gender, blood_type, address, user_type)
SELECT 'Blood Bank Staff', 'staff@example.com', '$2y$10$BfK6nW8TDlXgb.Ek0.w0sOUtUAI.jTDNEM.R7Ubnz1CvtnSdQ1aRC', '011 555 0104', 'Other', 'O+', 'Johannesburg Blood Centre', 'Staff'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'staff@example.com');

UPDATE users
SET password = '$2y$10$BfK6nW8TDlXgb.Ek0.w0sOUtUAI.jTDNEM.R7Ubnz1CvtnSdQ1aRC',
    user_type = 'Staff'
WHERE email = 'staff@example.com';

INSERT INTO users (full_name, email, password, phone, gender, blood_type, address, user_type)
SELECT 'Thabo Mokoena', 'donor@example.com', '$2y$10$PuZtHk5suaV7LE8RIlk69eCoFNjdjj88Zhum5lRpPB.K7yh8pC6fO', '082 555 0101', 'Male', 'O+', 'Soweto, Johannesburg', 'Donor'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'donor@example.com');

UPDATE users
SET password = '$2y$10$PuZtHk5suaV7LE8RIlk69eCoFNjdjj88Zhum5lRpPB.K7yh8pC6fO',
    user_type = 'Donor'
WHERE email = 'donor@example.com';

INSERT INTO users (full_name, email, password, phone, gender, blood_type, address, user_type)
SELECT 'Naledi Dlamini', 'donor.two@example.com', '$2y$10$PuZtHk5suaV7LE8RIlk69eCoFNjdjj88Zhum5lRpPB.K7yh8pC6fO', '083 555 0102', 'Female', 'A-', 'Pretoria', 'Donor'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'donor.two@example.com');

UPDATE users
SET password = '$2y$10$PuZtHk5suaV7LE8RIlk69eCoFNjdjj88Zhum5lRpPB.K7yh8pC6fO',
    user_type = 'Donor'
WHERE email = 'donor.two@example.com';

INSERT INTO users (full_name, email, password, phone, gender, blood_type, address, user_type)
SELECT 'Lerato Khumalo', 'recipient@example.com', '$2y$10$29T1x4yD0FaHCGgJlCQcAe2VLxuQnYhRFHBonZbrlBMXQKAaFFUlO', '084 555 0103', 'Female', 'B+', 'Sandton, Johannesburg', 'Recipient'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'recipient@example.com');

UPDATE users
SET password = '$2y$10$29T1x4yD0FaHCGgJlCQcAe2VLxuQnYhRFHBonZbrlBMXQKAaFFUlO',
    user_type = 'Recipient'
WHERE email = 'recipient@example.com';

-- Give existing schema rows useful demo stock, then add any missing blood types.
UPDATE blood_inventory
SET quantity_ml = CASE blood_type
  WHEN 'A+' THEN 2250
  WHEN 'A-' THEN 900
  WHEN 'B+' THEN 1800
  WHEN 'B-' THEN 450
  WHEN 'AB+' THEN 1350
  WHEN 'AB-' THEN 450
  WHEN 'O+' THEN 3150
  WHEN 'O-' THEN 1800
  ELSE quantity_ml
END
WHERE blood_type IN ('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-');

INSERT INTO blood_inventory (blood_type, quantity_ml)
SELECT seed.blood_type, seed.quantity_ml
FROM (
  SELECT 'A+' AS blood_type, 2250 AS quantity_ml UNION ALL
  SELECT 'A-', 900 UNION ALL
  SELECT 'B+', 1800 UNION ALL
  SELECT 'B-', 450 UNION ALL
  SELECT 'AB+', 1350 UNION ALL
  SELECT 'AB-', 450 UNION ALL
  SELECT 'O+', 3150 UNION ALL
  SELECT 'O-', 1800
) AS seed
WHERE NOT EXISTS (
  SELECT 1 FROM blood_inventory inventory
  WHERE inventory.blood_type = seed.blood_type
);

INSERT INTO donations (donor_id, blood_type, quantity_ml, status, date_donated)
SELECT u.id, 'O+', 450, 'Approved', DATE_SUB(CURDATE(), INTERVAL 30 DAY)
FROM users u
WHERE u.email = 'donor@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM donations d
    WHERE d.donor_id = u.id AND d.blood_type = 'O+' AND d.quantity_ml = 450
      AND d.status = 'Approved' AND d.date_donated = DATE_SUB(CURDATE(), INTERVAL 30 DAY)
  );

INSERT INTO donations (donor_id, blood_type, quantity_ml, status, date_donated)
SELECT u.id, 'A-', 450, 'Pending', CURDATE()
FROM users u
WHERE u.email = 'donor.two@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM donations d
    WHERE d.donor_id = u.id AND d.status = 'Pending' AND d.date_donated = CURDATE()
  );

INSERT INTO appointments (donor_id, appointment_date, location, status)
SELECT u.id, DATE_ADD(CURDATE(), INTERVAL 7 DAY) + INTERVAL 10 HOUR,
       'Johannesburg Blood Centre', 'Scheduled'
FROM users u
WHERE u.email = 'donor@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM appointments a
    WHERE a.donor_id = u.id AND a.status = 'Scheduled'
  );

INSERT INTO requests (recipient_id, blood_type, quantity_ml, status)
SELECT u.id, 'B+', 450, 'Pending'
FROM users u
WHERE u.email = 'recipient@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM requests r
    WHERE r.recipient_id = u.id AND r.blood_type = 'B+'
      AND r.quantity_ml = 450 AND r.status = 'Pending'
  );

INSERT INTO messages (sender_id, receiver_id, subject, message)
SELECT admin_user.id, recipient_user.id, 'Welcome to the Blood Donation Platform',
       'Your demo recipient account is ready. You can now submit and track blood requests.'
FROM users admin_user
JOIN users recipient_user
  ON recipient_user.email = 'recipient@example.com'
WHERE admin_user.email = 'admin@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM messages m
    WHERE m.sender_id = admin_user.id AND m.receiver_id = recipient_user.id
      AND m.subject = 'Welcome to the Blood Donation Platform'
  );

INSERT INTO admins_logs (admin_id, action)
SELECT u.id, 'Demo database seeded'
FROM users u
WHERE u.email = 'admin@example.com'
  AND NOT EXISTS (
    SELECT 1 FROM admins_logs log
    WHERE log.admin_id = u.id AND log.action = 'Demo database seeded'
  );

COMMIT;
