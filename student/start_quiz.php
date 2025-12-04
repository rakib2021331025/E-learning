<?php
session_start();
include '../dbconnection.php';

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    header("Location: ../loginorsignup.php");
    exit();
}

if(!isset($_GET['quiz_id']) || empty($_GET['quiz_id'])){
    die("<h3>No Quiz Selected</h3>");
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz
$sql = "SELECT * FROM quizzes WHERE quiz_id = $quiz_id";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $quiz = $result->fetch_assoc();

    // CSS Styling
    echo <<<STYLE
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
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
.options input[type="radio"] {
    margin-right: 8px;
}
.option-label {
    display: block;
    padding: 8px 12px;
    margin: 5px 0;
    border-radius: 8px;
    background: #e9ecef;
    cursor: pointer;
    transition: background 0.2s;
}
.option-label:hover {
    background: #d1e7ff;
}
.submit-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: #2196F3;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}
.submit-btn:hover {
    background: #0b7dda;
}
</style>
<div class="quiz-container">
STYLE;

    // Quiz title
    echo "<h2>".htmlspecialchars($quiz['quiz_title'])."</h2>";

    // Fetch questions
    $qsql = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
    $qresult = $conn->query($qsql);

    if($qresult && $qresult->num_rows > 0){
        echo '<form action="submit_quiz.php" method="POST">';
        echo '<input type="hidden" name="quiz_id" value="'.$quiz_id.'">';

        $i = 1;
        while($row = $qresult->fetch_assoc()){
            echo '<div class="question">';
            echo "<b>Q".$i.": ".htmlspecialchars($row['question_text'])."</b><br>";
            echo '<div class="options">';
            $options = ['A'=>'option_a', 'B'=>'option_b', 'C'=>'option_c', 'D'=>'option_d'];
            foreach($options as $key => $col){
                $option_text = htmlspecialchars($row[$col]);
                echo '<label class="option-label">';
                echo '<input type="radio" name="answers['.$row['quiz_id'].']" value="'.$key.'" required> '.$option_text;
                echo '</label>';
            }
            echo '</div>'; // options
            echo '</div>'; // question
            $i++;
        }

        echo '<input type="submit" class="submit-btn" value="Submit Quiz">';
        echo '</form>';
    } else {
        echo "<p>No questions found for this quiz.</p>";
    }

    echo '</div>'; // quiz-container
} else {
    echo "<p>Quiz not found.</p>";
}
?>
