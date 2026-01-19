USE SurveyProgram;
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    registration_no VARCHAR(50) NOT NULL UNIQUE,
    survey_progress ENUM('pending','completed') NOT NULL DEFAULT 'pending',
    status ENUM('active','inactive') DEFAULT 'active',
    batch_section_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (batch_section_id) 
        REFERENCES batch_sections(id)
        ON DELETE CASCADE
);

SELECT * FROM user;
SELECT * FROM user;
SELECT * FROM user;
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO admins (username, email, password, role) 
VALUES (
    'Shaharyar', 
    'aulakhshaharyar@gmail.com', 
    '$2a$12$DbrZO6hAtTG9IfEXF5VOQerh2A20COhsxafFw7X76qVOCg01g3QWy', 
    'admin'
);

CREATE TABLE ActivityLog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM('login', 'logout', 'survey_start', 'survey_submit') NOT NULL,
    details JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);


CREATE TABLE survey_progress (
    -- id column hata diya hai --
    user_id INT NOT NULL,
    year_session VARCHAR(50) NOT NULL, -- e.g. 2026(Spring)
    student_semester INT NOT NULL,
    
    -- Data Columns
    section_1 JSON DEFAULT NULL,
    section_2 JSON DEFAULT NULL,
    section_3 JSON DEFAULT NULL,
    section_4 JSON DEFAULT NULL,
    section_5 JSON DEFAULT NULL,
    
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, year_session),
    
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
SELECT * FROM survey_progress;
DROP TABLE if exists survey_progress;
CREATE TABLE batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_year VARCHAR(20) NOT NULL UNIQUE, -- e.g., '2022-2026'
    current_semester INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SELECT * FROM batches;
CREATE TABLE batch_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    section_name VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id)
        REFERENCES batches(id)
        ON DELETE CASCADE,
    UNIQUE KEY unique_batch_section (batch_id, section_name)
);
SELECT * FROM batch_sections;

SELECT b.current_semester, COUNT(u.id) as count 
                FROM batches b
                LEFT JOIN batch_sections bs ON b.id = bs.batch_id
                LEFT JOIN user u ON bs.id = u.batch_section_id AND u.status = 'active'
                WHERE b.status = 'enable'
                GROUP BY b.current_semester



                INSERT INTO user (username, email, registration_no, survey_progress, status, batch_section_id, created_at) VALUES 
-- Batch ID 1 (Assuming Semester 8)
('Zainab Bibi', 'zainab.bibi@example.com', 'BSCS-M1-22-10', 'completed', 'active', 1, NOW()),
('Omar Farooq', 'omar.farooq@example.com', 'BSCS-M1-22-11', 'completed', 'active', 1, NOW()),
('Ayesha Khan', 'ayesha.khan@example.com', 'BSCS-M1-22-12', 'pending', 'active', 1, NOW()),
('Hamza Ali', 'hamza.ali@example.com', 'BSCS-M1-22-13', 'completed', 'active', 1, NOW()),
('Hira Mani', 'hira.mani@example.com', 'BSCS-M1-22-14', 'pending', 'active', 1, NOW()),

-- Batch ID 4 (Assuming Semester 6)
('Saad Rafiq', 'saad.rafiq@example.com', 'BSCS-M1-23-05', 'completed', 'active', 4, NOW()),
('Mariam Yusuf', 'mariam.yusuf@example.com', 'BSCS-M1-23-06', 'pending', 'active', 4, NOW()),
('Bilal Ahmed', 'bilal.ahmed@example.com', 'BSCS-M1-23-07', 'completed', 'active', 4, NOW()),
('Sana Mir', 'sana.mir@example.com', 'BSCS-M1-23-08', 'pending', 'active', 4, NOW()),
('Taimoor Khan', 'taimoor.khan@example.com', 'BSCS-M1-23-09', 'pending', 'active', 4, NOW()),

-- Batch ID 19 (Assuming Semester 2)
('Rizwan Beyg', 'rizwan.beyg@example.com', 'BSCS-M1-25-15', 'completed', 'active', 19, NOW()),
('Nida Yasir', 'nida.yasir@example.com', 'BSCS-M1-25-16', 'pending', 'active', 19, NOW()),
('Fahad Mustafa', 'fahad.mustafa@example.com', 'BSCS-M1-25-17', 'completed', 'active', 19, NOW()),
('Mahira Khan', 'mahira.khan@example.com', 'BSCS-M1-25-18', 'pending', 'active', 19, NOW()),
('Fawad Khan', 'fawad.khan@example.com', 'BSCS-M1-25-19', 'pending', 'active', 19, NOW()),

-- Mixed Batch IDs (Varied)
('Atif Aslam', 'atif.aslam@example.com', 'BSCS-M1-22-45', 'pending', 'active', 1, NOW()),
('Momina Mustehsan', 'momina.m@example.com', 'BSCS-M1-23-46', 'completed', 'active', 4, NOW()),
('Ali Zafar', 'ali.zafar@example.com', 'BSCS-M1-25-47', 'pending', 'active', 19, NOW()),
('Rahat Fateh', 'rahat.fateh@example.com', 'BSCS-M1-22-48', 'pending', 'active', 1, NOW()),
('Abida Parveen', 'abida.parveen@example.com', 'BSCS-M1-23-49', 'pending', 'active', 4, NOW());


INSERT INTO user (username, email, registration_no, survey_progress, status, batch_section_id, created_at) VALUES 

INSERT INTO user (username, email, registration_no, survey_progress, status, batch_section_id, created_at) VALUES 

-- ===================================================
-- SEMESTER 6 (Batch ID 2) - Using Section IDs 7
-- ===================================================
('Hamza Abbasi', 'hamza.abb@example.com', 'BSCS-M1-23-10', 'completed', 'active', 7, NOW()),
('Maya Ali', 'maya.ali@example.com', 'BSCS-M1-23-11', 'completed', 'active', 7, NOW()),
('Humayun Saeed', 'humayun.s@example.com', 'BSCS-M1-23-13', 'completed', 'active', 7, NOW()),
('Kubra Khan', 'kubra.k@example.com', 'BSCS-M1-23-01', 'completed', 'active', 7, NOW()),
('Ahsan Khan', 'ahsan.k@example.com', 'BSCS-M1-23-15', 'completed', 'active', 7, NOW()),

-- ===================================================
-- SEMESTER 4 (Batch ID 3) - Using Section IDs 13 & 14
-- ===================================================
('Yumna Zaidi', 'yumna.z@example.com', 'BSCS-M1-24-21', 'completed', 'active', 13, NOW()),
('Wahaj Ali', 'wahaj.ali@example.com', 'BSCS-M1-24-22', 'completed', 'active', 13, NOW()),
('Sajal Aly', 'sajal.aly@example.com', 'BSCS-M1-24-23', 'completed', 'active', 13, NOW()),
('Ahad Raza Mir', 'ahad.raza@example.com', 'BSCS-M1-24-24', 'completed', 'active', 13, NOW()),
('Iqra Aziz', 'iqra.aziz@example.com', 'BSCS-M1-24-25', 'completed', 'active', 13, NOW()),

('Yasir Hussain', 'yasir.h@example.com', 'BSCS-M2-24-26', 'completed', 'active', 14, NOW()),
('Farhan Saeed', 'farhan.s@example.com', 'BSCS-M2-24-27', 'completed', 'active', 14, NOW()),
('Urwa Hocane', 'urwa.h@example.com', 'BSCS-M2-24-28', 'completed', 'active', 14, NOW()),
('Mawra Hocane', 'mawra.h@example.com', 'BSCS-M2-24-29', 'completed', 'active', 14, NOW()),
('Imran Abbas', 'imran.abbas@example.com', 'BSCS-M2-24-30', 'completed', 'active', 14, NOW());





-- 1. Insert Negative Survey Data for Ali (27) and Sara (28)
INSERT INTO survey_progress (user_id, year_session, student_semester, section_1, section_2, section_3, section_4, section_5, started_at, updated_at) VALUES 
(
    27, 
    '2026(Spring)', 
    8, 
    -- Section 1: Faculty (Very Poor)
    '{"q_teacher_pace": "1", "q_teacher_clarity": "2", "q_teacher_examples": "1", "q_teacher_fairness": "1", "q_teacher_knowledge": "2", "q_teacher_engagement": "1", "q_teacher_punctuality": "1", "q_teacher_preparedness": "1", "q_teacher_approachability": "1", "q_teacher_professionalism": "1", "section_number": "1"}',
    -- Section 2: Labs (Broken stuff)
    '{"q_lab_access": "1", "q_lab_support": "1", "q_lab_hardware": "1", "q_lab_internet": "1", "q_lab_software": "1", "q_class_furniture": "2", "q_lab_peripherals": "1", "q_class_multimedia": "1", "q_class_cleanliness": "2", "q_class_ventilation": "1", "section_number": "2"}',
    -- Section 3: Environment (Unsafe)
    '{"env_safety": "1", "env_greenery": "2", "env_discipline": "1", "env_cleanliness": "1", "env_peacefulness": "1", "env_admin_respect": "1", "env_gender_respect": "1", "env_security_staff": "2", "env_complaint_system": "1", "env_harassment_policy": "1", "section_number": "3"}',
    -- Section 4: Transport/Canteen (Expensive/Bad)
    '{"cant_price": "1", "trans_cost": "1", "trans_staff": "2", "cant_hygiene": "1", "cant_quality": "1", "cant_service": "1", "cant_variety": "1", "trans_routes": "1", "trans_condition": "1", "trans_punctuality": "1", "section_number": "4"}',
    -- Section 5: Complaint
    '{"final_feedback": "Absolutely terrible experience. Teachers never come on time and labs have no internet.", "complaint_target": "CS Department", "section_number": "5"}',
    NOW(), NOW()
),
(
    28, 
    '2026(Spring)', 
    8, 
    -- Section 1: Faculty (Poor)
    '{"q_teacher_pace": "2", "q_teacher_clarity": "1", "q_teacher_examples": "2", "q_teacher_fairness": "1", "q_teacher_knowledge": "2", "q_teacher_engagement": "2", "q_teacher_punctuality": "1", "q_teacher_preparedness": "2", "q_teacher_approachability": "1", "q_teacher_professionalism": "2", "section_number": "1"}',
    -- Section 2: Labs (Poor)
    '{"q_lab_access": "2", "q_lab_support": "1", "q_lab_hardware": "2", "q_lab_internet": "1", "q_lab_software": "2", "q_class_furniture": "1", "q_lab_peripherals": "2", "q_class_multimedia": "1", "q_class_cleanliness": "1", "q_class_ventilation": "2", "section_number": "2"}',
    -- Section 3: Environment (Unsafe)
    '{"env_safety": "2", "env_greenery": "3", "env_discipline": "1", "env_cleanliness": "2", "env_peacefulness": "2", "env_admin_respect": "1", "env_gender_respect": "1", "env_security_staff": "1", "env_complaint_system": "1", "env_harassment_policy": "1", "section_number": "3"}',
    -- Section 4: Transport/Canteen (Bad)
    '{"cant_price": "1", "trans_cost": "2", "trans_staff": "1", "cant_hygiene": "1", "cant_quality": "2", "cant_service": "1", "cant_variety": "1", "trans_routes": "2", "trans_condition": "1", "trans_punctuality": "1", "section_number": "4"}',
    -- Section 5: Complaint
    '{"final_feedback": "Transport buses are broken and canteen food is unhygienic.", "complaint_target": "Transport Office", "section_number": "5"}',
    NOW(), NOW()
);

-- 2. Update Users Status to Completed
UPDATE user 
SET survey_progress = 'completed' 
WHERE id IN (27, 28);

-- 1. Insert Detailed Survey Data for Urwa (87) and Ahad (83) - Semester 4
INSERT INTO survey_progress (user_id, year_session, student_semester, section_1, section_2, section_3, section_4, section_5, started_at, updated_at) VALUES 
(
    87, -- Urwa Hocane (Positive Review)
    '2026(Spring)', 
    4, 
    -- Section 1: Faculty (Excellent)
    '{"q_teacher_pace": "4", "q_teacher_clarity": "5", "q_teacher_examples": "5", "q_teacher_fairness": "5", "q_teacher_knowledge": "5", "q_teacher_engagement": "4", "q_teacher_punctuality": "5", "q_teacher_preparedness": "5", "q_teacher_approachability": "5", "q_teacher_professionalism": "5", "section_number": "1"}',
    -- Section 2: Labs (Good)
    '{"q_lab_access": "4", "q_lab_support": "5", "q_lab_hardware": "4", "q_lab_internet": "4", "q_lab_software": "5", "q_class_furniture": "4", "q_lab_peripherals": "4", "q_class_multimedia": "5", "q_class_cleanliness": "5", "q_class_ventilation": "4", "section_number": "2"}',
    -- Section 3: Environment (Safe)
    '{"env_safety": "5", "env_greenery": "5", "env_discipline": "4", "env_cleanliness": "5", "env_peacefulness": "4", "env_admin_respect": "5", "env_gender_respect": "5", "env_security_staff": "5", "env_complaint_system": "4", "env_harassment_policy": "5", "section_number": "3"}',
    -- Section 4: Transport (Satisfactory)
    '{"cant_price": "3", "trans_cost": "4", "trans_staff": "5", "cant_hygiene": "4", "cant_quality": "4", "cant_service": "5", "cant_variety": "3", "trans_routes": "4", "trans_condition": "4", "trans_punctuality": "5", "section_number": "4"}',
    -- Section 5: Feedback
    '{"final_feedback": "Everything is going great this semester. The lab equipment is much better now.", "complaint_target": "General", "section_number": "5"}',
    NOW(), NOW()
),
(
    83, -- Ahad Raza Mir (Average/Mixed Review)
    '2026(Spring)', 
    4, 
    -- Section 1: Faculty (Average)
    '{"q_teacher_pace": "3", "q_teacher_clarity": "4", "q_teacher_examples": "3", "q_teacher_fairness": "4", "q_teacher_knowledge": "4", "q_teacher_engagement": "3", "q_teacher_punctuality": "3", "q_teacher_preparedness": "4", "q_teacher_approachability": "3", "q_teacher_professionalism": "4", "section_number": "1"}',
    -- Section 2: Labs (Needs Improvement)
    '{"q_lab_access": "3", "q_lab_support": "3", "q_lab_hardware": "3", "q_lab_internet": "2", "q_lab_software": "4", "q_class_furniture": "3", "q_lab_peripherals": "3", "q_class_multimedia": "2", "q_class_cleanliness": "3", "q_class_ventilation": "3", "section_number": "2"}',
    -- Section 3: Environment (Okay)
    '{"env_safety": "4", "env_greenery": "4", "env_discipline": "3", "env_cleanliness": "3", "env_peacefulness": "3", "env_admin_respect": "4", "env_gender_respect": "4", "env_security_staff": "4", "env_complaint_system": "3", "env_harassment_policy": "4", "section_number": "3"}',
    -- Section 4: Transport (Issues)
    '{"cant_price": "2", "trans_cost": "3", "trans_staff": "4", "cant_hygiene": "3", "cant_quality": "3", "cant_service": "4", "cant_variety": "2", "trans_routes": "3", "trans_condition": "3", "trans_punctuality": "2", "section_number": "4"}',
    -- Section 5: Feedback
    '{"final_feedback": "Internet speed in labs is very slow, please upgrade it.", "complaint_target": "IT Department", "section_number": "5"}',
    NOW(), NOW()
)
ON DUPLICATE KEY UPDATE 
section_1 = VALUES(section_1), 
section_2 = VALUES(section_2), 
section_3 = VALUES(section_3), 
section_4 = VALUES(section_4), 
section_5 = VALUES(section_5),
updated_at = NOW();

-- 2. Ensure Status is Completed (Safe to run again)
UPDATE user 
SET survey_progress = 'completed' 
WHERE id IN (87, 83);


-- 1. Insert Detailed Survey Data for Hamza Abbasi (75) - Semester 6
INSERT INTO survey_progress (user_id, year_session, student_semester, section_1, section_2, section_3, section_4, section_5, started_at, updated_at) VALUES 
(
    75, 
    '2026(Spring)', 
    6, 
    -- Section 1: Faculty (Mostly Good/4s)
    '{"q_teacher_pace": "4", "q_teacher_clarity": "4", "q_teacher_examples": "5", "q_teacher_fairness": "4", "q_teacher_knowledge": "5", "q_teacher_engagement": "4", "q_teacher_punctuality": "5", "q_teacher_preparedness": "4", "q_teacher_approachability": "3", "q_teacher_professionalism": "5", "section_number": "1"}',
    -- Section 2: Labs (Excellent/5s)
    '{"q_lab_access": "5", "q_lab_support": "5", "q_lab_hardware": "5", "q_lab_internet": "4", "q_lab_software": "5", "q_class_furniture": "4", "q_lab_peripherals": "5", "q_class_multimedia": "5", "q_class_cleanliness": "5", "q_class_ventilation": "4", "section_number": "2"}',
    -- Section 3: Environment (Good)
    '{"env_safety": "5", "env_greenery": "4", "env_discipline": "4", "env_cleanliness": "5", "env_peacefulness": "4", "env_admin_respect": "4", "env_gender_respect": "5", "env_security_staff": "5", "env_complaint_system": "3", "env_harassment_policy": "5", "section_number": "3"}',
    -- Section 4: Transport (Average/3s)
    '{"cant_price": "3", "trans_cost": "3", "trans_staff": "4", "cant_hygiene": "4", "cant_quality": "4", "cant_service": "4", "cant_variety": "3", "trans_routes": "3", "trans_condition": "3", "trans_punctuality": "4", "section_number": "4"}',
    -- Section 5: Feedback
    '{"final_feedback": "The labs are perfect for our AI projects, but the canteen needs more food variety.", "complaint_target": "Canteen", "section_number": "5"}',
    NOW(), NOW()
)
ON DUPLICATE KEY UPDATE 
section_1 = VALUES(section_1), 
section_2 = VALUES(section_2), 
section_3 = VALUES(section_3), 
section_4 = VALUES(section_4), 
section_5 = VALUES(section_5),
updated_at = NOW();

-- 2. Ensure Status is Completed
UPDATE user 
SET survey_progress = 'completed' 
WHERE id = 75;