CREATE DATABASE IF NOT EXISTS api_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS api_db.students;

CREATE TABLE api_db.students (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(50)  NOT NULL,
  email      VARCHAR(100) NOT NULL UNIQUE,
  phone      CHAR(10)     NOT NULL,
  course     VARCHAR(20)  NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO api_db.students (name, email, phone, course) VALUES
('Aarav Sharma',   'aarav.sharma@example.com',   '9876500001', 'BCA'),
('Vivaan Patel',   'vivaan.patel@example.com',   '9876500002', 'MCA'),
('Aditya Desai',   'aditya.desai@example.com',   '9876500003', 'B.Tech'),
('Diya Mehta',     'diya.mehta@example.com',     '9876500004', 'BCA'),
('Ishaan Joshi',   'ishaan.joshi@example.com',   '9876500005', 'M.Tech'),
('Ananya Shah',    'ananya.shah@example.com',    '9876500006', 'BSc IT'),
('Kabir Singh',    'kabir.singh@example.com',    '9876500007', 'MCA'),
('Riya Desai',     'riya.desai@example.com',     '9876500008', 'B.Tech'),
('Arjun Nair',     'arjun.nair@example.com',     '9876500009', 'BCA'),
('Meera Iyer',     'meera.iyer@example.com',     '9876500010', 'MCA'),
('Rohan Gupta',    'rohan.gupta@example.com',    '9876500011', 'B.Tech'),
('Sneha Kulkarni', 'sneha.kulkarni@example.com', '9876500012', 'BSc IT');