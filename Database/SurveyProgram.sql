USE SurveyProgram;
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    registration_no VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO user (username, email, registration_no, status) 
VALUES ('Muhammad Shaharyar', 'muhammadshaharyaraulakh@gmail.com', 'BSCS-M1-22-08', 'active');


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
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    section_number INT NOT NULL, 
    section_data JSON NOT NULL, 
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, section_number) 
);

DROP TABLE IF EXISTS survey_progress;
DROP TABLE IF EXISTS ActivityLog;
DROP TABLE IF EXISTS user;