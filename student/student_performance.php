<?php
session_start();
include '../dbconnection.php';

// Check if student is logged in
$student_email = $_SESSION['stu_email'] ?? '';
if(empty($student_email) || empty($_SESSION['is_login'])){
    header("Location: ../loginorsignup.php");
    exit();
}

// Get student info
$stu_sql = "SELECT * FROM student WHERE stu_email=?";
$stmt = $conn->prepare($stu_sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$stu_result = $stmt->get_result();
$student = $stu_result->fetch_assoc();

// CSS
echo <<<STYLE
<style>
body { font-family: Arial; background:#f5f5f5; padding:20px; }
.container { max-width:900px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#333; margin-bottom:20px;}
.card { background:#e9ecef; padding:15px; border-radius:8px; margin-bottom:20px;}
.card h3 { color:#007bff; margin-bottom:10px;}
table { width:100%; border-collapse:collapse; margin-bottom:10px;}
th, td { border:1px solid #ccc; padding:8px; text-align:center;}
th { background:#007bff; color:#fff;}
tr:nth-child(even){background:#f2f2f2;}
</style>
STYLE;

// Page Header
echo "<div class='container'>";
echo "<h2>Student Performance</h2>";
echo "<p><b>Email:</b> ".htmlspecialchars($student_email)."</p>";

// Fetch enrolled courses using stu_email
$course_sql = "SELECT c.course_id, c.course_name 
               FROM course c
               JOIN course_order co ON c.course_id = co.course_id
               WHERE co.stu_email=?";
$stmt_course = $conn->prepare($course_sql);
$stmt_course->bind_param("s", $student_email);
$stmt_course->execute();
$course_result = $stmt_course->get_result();

if($course_result->num_rows > 0){
    while($course = $course_result->fetch_assoc()){
        $course_id = $course['course_id'];
        echo "<div class='card'>";
        echo "<h3>".htmlspecialchars($course['course_name'])."</h3>";

        // Assignments
        echo "<h4>Assignments</h4>";
        echo "<table><tr><th>Assignment</th><th>Marks</th><th>Status</th></tr>";
        $assign_sql = "SELECT a.title, s.marks, s.status
                       FROM assignments a
                       LEFT JOIN assignment_submissions s
                       ON a.id = s.assignment_id AND s.student_email=?
                       WHERE a.course_id=?";
        $stmt_assign = $conn->prepare($assign_sql);
        $stmt_assign->bind_param("si", $student_email, $course_id);
        $stmt_assign->execute();
        $assign_result = $stmt_assign->get_result();
        if($assign_result->num_rows > 0){
            while($a = $assign_result->fetch_assoc()){
                $marks = $a['marks'] ?? '-';
                $status = $a['status'] ?? '-';
                echo "<tr><td>".htmlspecialchars($a['title'])."</td><td>$marks</td><td>$status</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No assignments submitted</td></tr>";
        }
        echo "</table>";

        // Quizzes
        echo "<h4>Quizzes</h4>";
        echo "<table><tr><th>Quiz</th><th>Obtained Marks</th></tr>";
        $quiz_sql = "SELECT q.quiz_title, r.obtained_marks
                     FROM quizzes q
                     LEFT JOIN quiz_results r
                     ON q.quiz_id = r.quiz_id AND r.student_email=?
                     WHERE q.course_id=?";
        $stmt_quiz = $conn->prepare($quiz_sql);
        $stmt_quiz->bind_param("si", $student_email, $course_id);
        $stmt_quiz->execute();
        $quiz_result = $stmt_quiz->get_result();
        if($quiz_result->num_rows > 0){
            while($q = $quiz_result->fetch_assoc()){
                $obtained = $q['obtained_marks'] ?? '-';
                echo "<tr><td>".htmlspecialchars($q['quiz_title'])."</td><td>$obtained</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No quizzes taken</td></tr>";
        }
        echo "</table>";

        echo "</div>"; // card
    }
} else {
    echo "<p>No enrolled courses found.</p>";
}

echo "</div>"; // container
?>
