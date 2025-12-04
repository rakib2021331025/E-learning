<?php
include "../dbconnection.php";
session_start();
date_default_timezone_set("Asia/Dhaka");

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}

// Fetch courses student is enrolled in
$student_courses = [];
$res = $conn->query("SELECT course_id FROM course_order WHERE stu_email='$student'");

if(!$res){
    die("Query Error: ".$conn->error);
}

while($row = $res->fetch_assoc()){
    $student_courses[] = $row['course_id'];
}

if(empty($student_courses)){
    die("<h3>You are not enrolled in any course!</h3>");
}

// Fetch only exams from these courses
$exam_res = $conn->query("SELECT * FROM exams WHERE course_id IN (".implode(',', $student_courses).") AND status='active' ORDER BY start_time ASC");
if(!$exam_res){
    die("Query Error: ".$conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Exams</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e9f5ff;
        }
        a {
            text-decoration: none;
            color: #fff;
            background-color: #28a745;
            padding: 6px 12px;
            border-radius: 5px;
            transition: 0.3s;
        }
        a:hover {
            background-color: #218838;
        }
        .note {
            text-align: center;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Available Exams</h2>
<?php if($exam_res->num_rows == 0){ ?>
    <p class="note">No exams available for your enrolled courses.</p>
<?php } else { ?>
<table>
<tr>
    <th>Exam Title</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Action</th>
</tr>

<?php while($exam = $exam_res->fetch_assoc()){ 
    $now = date("Y-m-d H:i:s");
    $exam_id = $exam['id'];
?>
<tr>
    <td><?php echo htmlspecialchars($exam['title']); ?></td>
    <td><?php echo $exam['start_time']; ?></td>
    <td><?php echo $exam['end_time']; ?></td>
    <td>
        <?php 
        if($now < $exam['start_time']){
            echo "Not started yet";
        } elseif($now > $exam['end_time']){
            echo "Exam finished";
        } else {
            echo "<a href='start_exam.php?exam_id=$exam_id'>Start Exam</a>";
        }
        ?>
    </td>
</tr>
<?php } ?>
</table>
<?php } ?>

</body>
</html>
