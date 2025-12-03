<?php
include "../dbconnection.php";
session_start();

// Fetch all courses
$course_res = $conn->query("SELECT * FROM course ORDER BY course_name ASC");

if(isset($_POST['createExam'])){
    $title = $conn->real_escape_string($_POST['title']);
    $course = intval($_POST['course_id']); // selected course ID
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $duration = intval($_POST['duration']);

    $sql = "INSERT INTO exams(title, course_id, start_time, end_time, duration, status)
            VALUES('$title', $course, '$start', '$end', $duration, 'active')";

    if($conn->query($sql)){
        $last_id = $conn->insert_id;
        echo "<p class='success'>Exam Created! <a href='add_examquestion.php?exam_id=$last_id'>Add Questions</a></p>";
        exit();
    } else {
        echo "<p class='error'>Error: ".$conn->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
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
        }
        form {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 30px auto;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="datetime-local"], select {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 20px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: #fff url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'><path fill='%23333' d='M7 10l5 5 5-5z'/></svg>") no-repeat right 10px center;
            background-size: 12px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .success a {
            color: #fff;
            background-color: #28a745;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }
        .success a:hover {
            background-color: #218838;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>Create Exam</h2>
<form method="POST">
    <label>Exam Title:</label>
    <input type="text" name="title" required>

    <label>Select Course:</label>
    <select name="course_id" required>
        <option value="">--Select Course--</option>
        <?php while($course = $course_res->fetch_assoc()){ ?>
            <option value="<?php echo $course['course_id']; ?>">
                <?php echo htmlspecialchars($course['course_name']); ?>
            </option>
        <?php } ?>
    </select>

    <label>Start Time:</label>
    <input type="datetime-local" name="start_time" required>

    <label>End Time:</label>
    <input type="datetime-local" name="end_time" required>

    <label>Duration (minutes):</label>
    <input type="number" name="duration" required>

    <div style="text-align:center;">
        <button name="createExam">Create Exam</button>
    </div>
</form>

</body>
</html>
