<?php
session_start();
include '../dbconnection.php';

// Check if student is logged in
if(!isset($_SESSION['stulogEmail'])){
    header("Location: login.php");
    exit();
}

// Validate course_id
if(!isset($_GET['course_id']) || empty($_GET['course_id'])){
    die("<h3>No Course Selected</h3>");
}

$course_id = intval($_GET['course_id']);

// CSS Styling
echo <<<STYLE
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    padding: 20px;
}
.container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h3 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
}
.quiz-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    margin-bottom: 12px;
    background: #f9f9f9;
    border-left: 5px solid #2196F3;
    border-radius: 8px;
    transition: background 0.3s, transform 0.2s;
}
.quiz-item:hover {
    background: #e1f0ff;
    transform: translateY(-2px);
}
.quiz-title {
    font-size: 16px;
    color: #333;
}
.start-btn {
    text-decoration: none;
    background: #28a745;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s;
}
.start-btn:hover {
    background: #218838;
}
</style>
<div class="container">
<h3>Available Quizzes</h3>
STYLE;

// Fetch quizzes for the course
$sql = "SELECT * FROM quizzes WHERE course_id = $course_id";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo '<div class="quiz-item">';
        echo '<span class="quiz-title">'.htmlspecialchars($row['quiz_title']).'</span>';
        echo '<a class="start-btn" href="start_quiz.php?quiz_id='.$row['quiz_id'].'">Start Quiz</a>';
        echo '</div>';
    }
} else {
    echo "<p style='text-align:center;color:#555;'>No quizzes available for this course.</p>";
}

echo '</div>'; // container
?>
