<?php
include "../dbconnection.php";
session_start();

// Fetch all exams
$res = $conn->query("SELECT * FROM exams ORDER BY start_time DESC");
?>

<h2>All Exams</h2>
<table border="1" cellpadding="6">
<tr>
    <th>Exam Title</th>
    <th>Course ID</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Action</th>
</tr>

<?php while($exam = $res->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($exam['title']); ?></td>
    <td><?php echo $exam['course_id']; ?></td>
    <td><?php echo $exam['start_time']; ?></td>
    <td><?php echo $exam['end_time']; ?></td>
    <td>
        <a href="evaluate_exam.php?exam_id=<?php echo $exam['id']; ?>">Evaluate</a>
    </td>
</tr>
<?php } ?>
</table>
