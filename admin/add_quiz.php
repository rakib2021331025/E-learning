<?php 
include('../dbconnection.php');
session_start();

if(isset($_POST['add_quiz'])){
    $course = $_POST['course_id'];
    $title = $_POST['quiz_title'];
    $marks = $_POST['total_marks'];

    $conn->query("INSERT INTO quizzes(course_id, quiz_title, total_marks)
                  VALUES('$course','$title','$marks')");

    $id = $conn->insert_id;

    header("Location: add_question.php?quiz_id=$id");
}
?>

<h2>Add New Quiz</h2>
<form method="POST">
    Course ID: <input type="text" name="course_id"><br><br>
    Quiz Title: <input type="text" name="quiz_title"><br><br>
    Total Marks: <input type="number" name="total_marks"><br><br>
    <button name="add_quiz">Add Quiz</button>
</form>
