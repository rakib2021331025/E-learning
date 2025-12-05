<?php
session_start();
include '../dbconnection.php';

// Check login
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';
if(empty($student) || empty($_SESSION['is_login'])){
    header("Location: ../loginorsignup.php");
    exit();
}

// Quiz ID check
if(!isset($_POST['quiz_id']) || empty($_POST['quiz_id'])){
    die("No quiz selected.");
}

$quiz_id = intval($_POST['quiz_id']);
$answers = $_POST['answers'] ?? [];

// IMPORTANT: Remove old quiz session (prevents showing old quiz)
unset($_SESSION['quiz_id']);

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS quiz_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    answer VARCHAR(10),
    is_correct TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_answer (quiz_id, question_id, student_email)
)");

$conn->query("CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    total_questions INT DEFAULT 0,
    obtained_marks INT DEFAULT 0,
    percentage DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_quiz_student (quiz_id, student_email)
)");

$score = 0;
$total_questions = count($answers);

// Loop answers
foreach($answers as $question_id => $answer){

    // Fetch question
    $q = $conn->query("SELECT * FROM questions WHERE id = $question_id");
    if(!$q || $q->num_rows == 0) continue;

    $row = $q->fetch_assoc();
    
    $correct = strtoupper(trim($row['correct_option']));
    $user_ans = strtoupper(trim($answer));
    $is_correct = ($correct === $user_ans) ? 1 : 0;
    if($is_correct) $score++;

    // Insert or Update Answer (Safe & Simple)
    $stmt = $conn->prepare("
        INSERT INTO quiz_answers (quiz_id, question_id, student_email, answer, is_correct)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            answer = VALUES(answer),
            is_correct = VALUES(is_correct)
    ");
    $stmt->bind_param("iissi", $quiz_id, $question_id, $student, $user_ans, $is_correct);
    $stmt->execute();
}

// Calculate result
$percentage = ($total_questions > 0) ? round(($score / $total_questions) * 100, 2) : 0;

// Save final quiz result
$stmt2 = $conn->prepare("
    INSERT INTO quiz_results (quiz_id, student_email, total_questions, obtained_marks, percentage)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        total_questions = VALUES(total_questions),
        obtained_marks = VALUES(obtained_marks),
        percentage = VALUES(percentage)
");
$stmt2->bind_param("isiid", $quiz_id, $student, $total_questions, $score, $percentage);
$stmt2->execute();

?>

<!DOCTYPE html>
<html>
<head>
<title>Quiz Result</title>
<style>
body { background:#f5f5f5; font-family: Arial; padding:20px; }
.box { max-width:600px; background:#fff; margin:auto; padding:25px; border-radius:10px; }
h2 { text-align:center; color:#333; }
.score { text-align:center; margin-top:15px; font-size:20px; }
</style>
</head>
<body>
<div class="box">
    <h2>Your Quiz Result</h2>
    <div class="score">
        <p><b>Score:</b> <?= $score ?> / <?= $total_questions ?></p>
        <p><b>Percentage:</b> <?= $percentage ?>%</p>
    </div>
    <div style="text-align:center; margin-top:25px;">
        <a href="studentdashboard.php" style="padding:10px 20px; background:#007bff; color:#fff; border-radius:6px; text-decoration:none;">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
