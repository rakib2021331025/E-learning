<?php 
include('../dbconnection.php');
session_start();

/*
----------------------------------------------------
CHECK IF course_id IS PASSED (FOR STUDENT)
----------------------------------------------------
If course_id is passed in URL → show quizzes of that course
If not passed → show all quizzes (for Admin)
----------------------------------------------------
*/

$course_id = $_GET['course_id'] ?? null;

// If course_id exists → filter by course
if ($course_id) {
    $q = $conn->query("SELECT * FROM quizzes WHERE course_id = '$course_id'");
} else {
    // No course_id → show all quizzes (Admin view)
    $q = $conn->query("SELECT * FROM quizzes");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz List</title>
</head>
<body>

<h2>All Quizzes</h2>

<!-- Only admin can add quiz -->
<a href="add_quiz.php">Add New Quiz</a>
<hr>

<?php 
if ($q->num_rows > 0) {
    while($row = $q->fetch_assoc()){ 
?>
        <p>
            <b><?php echo $row['quiz_title']; ?></b>  
            (Course ID: <?php echo $row['course_id']; ?>)

            <!-- Add question button -->
            <a href="add_question.php?quiz_id=<?php echo $row['quiz_id']; ?>">Add Question</a>

            <!-- Student give quiz -->
            <a href="start_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>">Start Quiz</a>
        </p>
<?php 
    }
} else {
    echo "<p>No quizzes found!</p>";
}
?>

</body>
</html>
