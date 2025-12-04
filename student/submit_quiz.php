<?php
session_start();
include '../dbconnection.php';

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    header("Location: ../loginorsignup.php");
    exit();
}

if(!isset($_POST['quiz_id']) || empty($_POST['quiz_id'])){
    die("No quiz selected.");
}

$quiz_id = intval($_POST['quiz_id']);
$answers = $_POST['answers'] ?? [];

// Create quiz_answers table if not exists
$table_check = $conn->query("SHOW TABLES LIKE 'quiz_answers'");
if($table_check->num_rows == 0){
    $create_table = "CREATE TABLE quiz_answers (
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
    $conn->query($create_table);
}

// Create quiz_results table if not exists
$table_check2 = $conn->query("SHOW TABLES LIKE 'quiz_results'");
if($table_check2->num_rows == 0){
    $create_table2 = "CREATE TABLE quiz_results (
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
    $conn->query($create_table2);
}

$score = 0;
$total_questions = count($answers);

// CSS Styling
echo <<<STYLE
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    padding: 20px;
}
.quiz-container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.quiz-container h2 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
}
.question {
    margin-bottom: 25px;
    padding: 15px;
    border-left: 5px solid #2196F3;
    background: #f9f9f9;
    border-radius: 8px;
}
.question b {
    font-size: 16px;
}
.option {
    padding: 5px 10px;
    margin: 4px 0;
    border-radius: 6px;
}
.correct {
    background: #d4edda;
    color: #155724;
    font-weight: bold;
}
.wrong {
    background: #f8d7da;
    color: #721c24;
    font-weight: bold;
}
.status {
    float: right;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 6px;
}
.status.correct {
    background: #28a745;
    color: #fff;
}
.status.wrong {
    background: #dc3545;
    color: #fff;
}
.score {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin-top: 20px;
}
</style>
<div class="quiz-container">
<h2>Quiz Result</h2>
STYLE;

// Loop through each answer
foreach($answers as $question_id => $answer){
    $question_id = intval($question_id);

    $sql = "SELECT question_text, option_a, option_b, option_c, option_d, correct_option 
            FROM questions WHERE id = $question_id";
    $result = $conn->query($sql);

    if($result && $row = $result->fetch_assoc()){
        $correct_option = trim(strtoupper($row['correct_option']));
        $user_answer = strtoupper(trim($answer));
        $is_correct = 0;

        // Status
        if($correct_option === $user_answer){
            $score++;
            $is_correct = 1;
            $status_class = "correct";
            $status_text = "Correct";
        } else {
            $status_class = "wrong";
            $status_text = "Wrong";
        }
        
        // Save answer to database
        $stmt = $conn->prepare("
            INSERT INTO quiz_answers (quiz_id, question_id, student_email, answer, is_correct)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE answer = ?, is_correct = ?
        ");
        // Check if unique constraint exists, if not use simple INSERT
        $check_duplicate = $conn->query("
            SELECT id FROM quiz_answers 
            WHERE quiz_id = $quiz_id AND question_id = $question_id AND student_email = '$student'
        ");
        
        if($check_duplicate && $check_duplicate->num_rows > 0){
            // Update existing
            $update_stmt = $conn->prepare("
                UPDATE quiz_answers 
                SET answer = ?, is_correct = ?
                WHERE quiz_id = ? AND question_id = ? AND student_email = ?
            ");
            $update_stmt->bind_param("siiis", $user_answer, $is_correct, $quiz_id, $question_id, $student);
            $update_stmt->execute();
        } else {
            // Insert new
            $insert_stmt = $conn->prepare("
                INSERT INTO quiz_answers (quiz_id, question_id, student_email, answer, is_correct)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert_stmt->bind_param("iissi", $quiz_id, $question_id, $student, $user_answer, $is_correct);
            $insert_stmt->execute();
        }

        echo '<div class="question">';
        echo '<b>Q: '.htmlspecialchars($row['question_text']).'</b>';
        echo '<span class="status '.$status_class.'">'.$status_text.'</span><br>';

        $options = ['A'=>'option_a', 'B'=>'option_b', 'C'=>'option_c', 'D'=>'option_d'];
        foreach($options as $key => $col){
            $option_text = htmlspecialchars($row[$col]);
            $classes = "option";

            if($key == $correct_option){
                $classes .= " correct";
            }
            if($key == $user_answer && $user_answer != $correct_option){
                $classes .= " wrong";
            }

            echo '<div class="'.$classes.'">'.$key.'. '.$option_text.'</div>';
        }

        echo '</div>';
    }
}
// Save/Update quiz result
$percentage = $total_questions > 0 ? round(($score / $total_questions) * 100, 2) : 0;

// Get course_id from quiz
$quiz_info = $conn->query("SELECT course_id FROM quizzes WHERE quiz_id = $quiz_id");
$course_id = 0;
if($quiz_info && $quiz_info->num_rows > 0){
    $quiz_row = $quiz_info->fetch_assoc();
    $course_id = $quiz_row['course_id'] ?? 0;
}

$check_result = $conn->query("
    SELECT id FROM quiz_results 
    WHERE quiz_id = $quiz_id AND student_email = '$student'
");

if($check_result && $check_result->num_rows > 0){
    // Update existing result
    $update_sql = "UPDATE quiz_results 
                   SET total_questions = $total_questions, 
                       obtained_marks = $score, 
                       percentage = $percentage
                   WHERE quiz_id = $quiz_id AND student_email = '$student'";
    if(!$conn->query($update_sql)){
        echo "<p style='color:red;'>Error updating quiz result: " . $conn->error . "</p>";
    }
} else {
    // Insert new result
    $insert_sql = "INSERT INTO quiz_results (quiz_id, student_email, total_questions, obtained_marks, percentage)
                   VALUES ($quiz_id, '$student', $total_questions, $score, $percentage)";
    if(!$conn->query($insert_sql)){
        echo "<p style='color:red;'>Error saving quiz result: " . $conn->error . "</p>";
    }
}


// Score display
$percentage = $total_questions > 0 ? round(($score / $total_questions) * 100, 2) : 0;
echo '<div class="score">';
echo '<div style="font-size: 28px; color: #28a745; margin-bottom: 10px;">Your Score: '.$score.' / '.$total_questions.'</div>';
echo '<div style="font-size: 20px; color: #666;">Percentage: '.$percentage.'%</div>';
echo '</div>';
echo '<div style="text-align: center; margin-top: 20px;">';
if($course_id > 0){
    echo '<a href="quiz_list.php?course_id='.$course_id.'" style="padding: 10px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; display: inline-block;">Back to Quiz List</a>';
} else {
    echo '<a href="studentdashboard.php" style="padding: 10px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; display: inline-block;">Back to Dashboard</a>';
}
echo '</div>';
echo '</div>'; // quiz-container
?>
