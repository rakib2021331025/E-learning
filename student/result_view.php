<?php
include "../dbconnection.php";
session_start();
if(!isset($_SESSION['stulogEmail'])) die();

$stu = $_SESSION['stulogEmail'];
$exam_id = intval($_GET['exam_id']);

$res = $conn->query("
SELECT SUM(obtained_mark) AS total
FROM exam_answers
WHERE exam_id=$exam_id AND student_email='$stu'
");
$total = $res->fetch_assoc()['total'] ?? 0;

echo "<h2>Your Score: $total</h2>";
echo "<p><a href='../admin/export_pdf.php?exam_id=$exam_id'>Download full exam results (teacher PDF)</a></p>";
?>