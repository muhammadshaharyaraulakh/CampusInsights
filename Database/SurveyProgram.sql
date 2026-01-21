USE SurveyProgram;
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    registration_no VARCHAR(50) NOT NULL UNIQUE,
    survey_progress ENUM('pending', 'completed') NOT NULL DEFAULT 'pending',
    status ENUM('active', 'inactive') DEFAULT 'active',
    batch_section_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_section_id) REFERENCES batch_sections(id) ON DELETE CASCADE
);
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
        'ALI',
        'ali@gmail.com',
        '$2a$12$DbrZO6hAtTG9IfEXF5VOQerh2A20COhsxafFw7X76qVOCg01g3QWy',
        'admin'
    );
CREATE TABLE ActivityLog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM(
        'login',
        'logout',
        'survey_start',
        'survey_submit'
    ) NOT NULL,
    details JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
CREATE TABLE survey_progress (
    user_id INT NOT NULL,
    year_session VARCHAR(50) NOT NULL,
    student_semester INT NOT NULL,
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
CREATE TABLE batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_year VARCHAR(20) NOT NULL UNIQUE,
    current_semester INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE batch_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    section_name VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE,
    UNIQUE KEY unique_batch_section (batch_id, section_name)
);
