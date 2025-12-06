<?php
/**
 * Database Setup Script
 * This file creates all necessary tables for Quiz, Assignment, and Exam features
 * Run this file once to set up the database structure
 */

include 'dbconnection.php';

echo "<h2>Setting up database tables...</h2>";

// 1. Create exams table
$sql_exams = "CREATE TABLE IF NOT EXISTS exams (
    exam_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_title VARCHAR(255) NOT NULL,
    course_id INT DEFAULT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    duration INT NOT NULL COMMENT 'minutes',
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_course (course_id),
    INDEX idx_status (status)
)";

if($conn->query($sql_exams)){
    echo "<p style='color:green;'>✓ exams table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating exams: " . $conn->error . "</p>";
}

// 2. Create exam_questions table
$sql_exam_questions = "CREATE TABLE IF NOT EXISTS exam_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    question_type ENUM('mcq', 'written', 'short', 'long') DEFAULT 'mcq',
    question_text TEXT NOT NULL,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option VARCHAR(5),
    image_path VARCHAR(500) NULL,
    marks DECIMAL(10,2) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE,
    INDEX idx_exam (exam_id)
)";

if($conn->query($sql_exam_questions)){
    echo "<p style='color:green;'>✓ exam_questions table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating exam_questions: " . $conn->error . "</p>";
}

// 3. Create exam_answers table
$sql_exam_answers = "CREATE TABLE IF NOT EXISTS exam_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    question_id INT NOT NULL,
    given_option VARCHAR(1),
    answer_text TEXT,
    file_path VARCHAR(500),
    obtained_mark DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_answer (exam_id, student_email, question_id),
    INDEX idx_exam_student (exam_id, student_email),
    INDEX idx_question (question_id)
)";

if($conn->query($sql_exam_answers)){
    echo "<p style='color:green;'>✓ exam_answers table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating exam_answers: " . $conn->error . "</p>";
}

// 4. Create exam_results table
$sql_exam_results = "CREATE TABLE IF NOT EXISTS exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    total_marks DECIMAL(10,2) DEFAULT 0,
    obtained_marks DECIMAL(10,2) DEFAULT 0,
    percentage DECIMAL(5,2) DEFAULT 0,
    status ENUM('pending', 'evaluated') DEFAULT 'pending',
    evaluated_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_exam_student (exam_id, student_email),
    INDEX idx_student (student_email)
)";

if($conn->query($sql_exam_results)){
    echo "<p style='color:green;'>✓ exam_results table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating exam_results: " . $conn->error . "</p>";
}

// 5. Create quizzes table (if not exists)
$sql_quizzes = "CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_title VARCHAR(255) NOT NULL,
    course_id INT DEFAULT NULL,
    description TEXT,
    total_marks INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_course (course_id)
)";

if($conn->query($sql_quizzes)){
    echo "<p style='color:green;'>✓ quizzes table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating quizzes: " . $conn->error . "</p>";
}

// 6. Create questions table (for quizzes)
$sql_questions = "CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option VARCHAR(1) NOT NULL,
    marks DECIMAL(10,2) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_quiz (quiz_id)
)";

if($conn->query($sql_questions)){
    echo "<p style='color:green;'>✓ questions table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating questions: " . $conn->error . "</p>";
}

// 7. Create quiz_answers table
$sql_quiz_answers = "CREATE TABLE IF NOT EXISTS quiz_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    answer VARCHAR(10),
    is_correct TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_quiz_student (quiz_id, student_email),
    INDEX idx_question (question_id)
)";

if($conn->query($sql_quiz_answers)){
    echo "<p style='color:green;'>✓ quiz_answers table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating quiz_answers: " . $conn->error . "</p>";
}

// 8. Create quiz_results table
$sql_quiz_results = "CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    total_questions INT DEFAULT 0,
    obtained_marks INT DEFAULT 0,
    percentage DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_quiz_student (quiz_id, student_email),
    INDEX idx_student (student_email)
)";

if($conn->query($sql_quiz_results)){
    echo "<p style='color:green;'>✓ quiz_results table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating quiz_results: " . $conn->error . "</p>";
}

// 9. Create assignments table (if not exists)
$sql_assignments = "CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_title VARCHAR(255) NOT NULL,
    course_id INT NOT NULL,
    description TEXT,
    due_date DATETIME,
    total_marks DECIMAL(10,2) DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_course (course_id)
)";

if($conn->query($sql_assignments)){
    echo "<p style='color:green;'>✓ assignments table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating assignments: " . $conn->error . "</p>";
}

// 10. Create assignment_submissions table
$sql_assignment_submissions = "CREATE TABLE IF NOT EXISTS assignment_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    marks DECIMAL(10,2) DEFAULT 0,
    feedback TEXT,
    status VARCHAR(50) DEFAULT 'Pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    evaluated_at TIMESTAMP NULL,
    INDEX idx_assignment (assignment_id),
    INDEX idx_student (student_email),
    INDEX idx_status (status)
)";

if($conn->query($sql_assignment_submissions)){
    echo "<p style='color:green;'>✓ assignment_submissions table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating assignment_submissions: " . $conn->error . "</p>";
}

// 11. Create live_classes table (if not exists)
$sql_live_classes = "CREATE TABLE IF NOT EXISTS live_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    room_name VARCHAR(255) NOT NULL,
    status ENUM('active', 'ended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_course (course_id),
    INDEX idx_status (status)
)";

if($conn->query($sql_live_classes)){
    echo "<p style='color:green;'>✓ live_classes table created/verified</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating live_classes: " . $conn->error . "</p>";
}

echo "<h3 style='color:green;'>Database setup completed!</h3>";
echo "<p><a href='admin/admindashboard.php'>Go to Admin Dashboard</a></p>";

$conn->close();
?>

