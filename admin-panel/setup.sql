CREATE DATABASE IF NOT EXISTS dashboard_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS dashboard_db.audit_logs;
DROP TABLE IF EXISTS dashboard_db.registrations;
DROP TABLE IF EXISTS dashboard_db.events;
DROP TABLE IF EXISTS dashboard_db.students;
DROP TABLE IF EXISTS dashboard_db.users;

CREATE TABLE dashboard_db.users (
  user_id       INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(30)  NOT NULL UNIQUE,
  email         VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin','student') NOT NULL DEFAULT 'student',
  status        ENUM('active','inactive') NOT NULL DEFAULT 'active',
  last_login    DATETIME NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE dashboard_db.students (
  student_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name  VARCHAR(50)  NOT NULL,
  email      VARCHAR(100) NOT NULL UNIQUE,
  course     VARCHAR(20)  NOT NULL
) ENGINE=InnoDB;

CREATE TABLE dashboard_db.events (
  event_id   INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(100) NOT NULL,
  category   VARCHAR(20)  NOT NULL,
  event_date DATE NOT NULL,
  venue      VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE dashboard_db.registrations (
  registration_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id      INT NOT NULL,
  event_id        INT NOT NULL,
  registered_at   DATETIME NOT NULL,
  UNIQUE KEY uq_student_event (student_id, event_id),
  CONSTRAINT fk_reg_student FOREIGN KEY (student_id) REFERENCES dashboard_db.students(student_id) ON DELETE CASCADE,
  CONSTRAINT fk_reg_event   FOREIGN KEY (event_id)   REFERENCES dashboard_db.events(event_id)     ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE dashboard_db.audit_logs (
  log_id     INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NULL,
  action     VARCHAR(30)  NOT NULL,
  details    VARCHAR(255) NOT NULL DEFAULT '',
  ip_address VARCHAR(45)  NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_log_user FOREIGN KEY (user_id) REFERENCES dashboard_db.users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO dashboard_db.students (full_name, email, course) VALUES
('Rahul Patel',    'rahul@example.com',  'BCA'),
('Priya Shah',     'priya@example.com',  'BTech'),
('Amit Mehta',     'amit@example.com',   'MCA'),
('Neha Joshi',     'neha@example.com',   'BSc IT'),
('Kunal Desai',    'kunal@example.com',  'BCA'),
('Riya Desai',     'riya@example.com',   'BTech'),
('Arjun Nair',     'arjun@example.com',  'BCA'),
('Meera Iyer',     'meera@example.com',  'MCA'),
('Rohan Gupta',    'rohan@example.com',  'BTech'),
('Sneha Kulkarni', 'sneha@example.com',  'BSc IT');

INSERT INTO dashboard_db.events (title, category, event_date, venue) VALUES
('Hackathon 2026',     'Technical', '2026-11-15', 'Main Auditorium'),
('Python Workshop',    'Workshop',  '2026-09-20', 'Computer Lab 2'),
('Cultural Night',     'Cultural',  '2026-12-02', 'Open Air Theatre'),
('Cricket Tournament', 'Sports',    '2026-10-25', 'College Ground'),
('AI Seminar',         'Seminar',   '2026-08-12', 'Seminar Hall'),
('Web Dev Workshop',   'Workshop',  '2026-05-18', 'Computer Lab 1');

INSERT INTO dashboard_db.registrations (student_id, event_id, registered_at) VALUES
(1,6,'2026-03-05 10:15:00'),(2,6,'2026-03-07 11:00:00'),
(3,6,'2026-04-02 09:30:00'),(4,6,'2026-04-10 14:20:00'),
(5,6,'2026-05-01 16:45:00'),
(1,5,'2026-06-12 12:10:00'),(2,5,'2026-06-15 13:00:00'),(6,5,'2026-06-20 10:05:00'),
(7,5,'2026-07-03 15:30:00'),(8,5,'2026-07-08 09:50:00'),(9,5,'2026-07-22 11:40:00'),
(1,2,'2026-08-02 10:00:00'),(3,2,'2026-08-05 10:30:00'),(4,2,'2026-08-11 17:10:00'),
(5,2,'2026-08-18 12:25:00'),(10,2,'2026-08-25 09:15:00'),
(2,4,'2026-09-02 08:45:00'),(6,4,'2026-09-09 14:05:00'),(7,4,'2026-09-14 16:20:00'),(8,4,'2026-09-21 11:35:00'),
(1,1,'2026-10-01 10:10:00'),(2,1,'2026-10-03 12:00:00'),(3,1,'2026-10-06 13:45:00'),
(9,1,'2026-10-09 15:15:00'),(4,3,'2026-10-11 09:00:00');