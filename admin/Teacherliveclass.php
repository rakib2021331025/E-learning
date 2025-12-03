<?php
include '../dbconnection.php';
session_start();

// Demo admin session (replace with actual login session)
$adminemail = $_SESSION['adminemail'] ?? "rakib@gmail.com"; 
$admin_id = $_SESSION['admin_id'] ?? 1; // database অনুযায়ী set করুন

// Fetch all courses
$sql = "SELECT * FROM course"; // table name ঠিক আছে কি check করুন
$courses = $conn->query($sql);

// Start Live Class
if(isset($_POST['start_class'])){
    $course_id = $_POST['course_id'];
    $room_name = "class_" . time();

    $stmt = $conn->prepare("INSERT INTO live_classes (course_id, admin_id, room_name) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $course_id, $admin_id, $room_name);

    if($stmt->execute()){
        echo "<p>Live class started for course ID $course_id: 
              <a href='../student/studentjoinclass.php?room=$room_name' target='_blank'>Join Class</a></p>";
    } else {
        echo "Error starting live class: " . $stmt->error;
    }
}
?>

<h2>Start Live Class</h2>
<form method="POST">
    <label for="course">Select Course:</label>
    <select name="course_id" id="course" required>
        <?php
        if($courses && $courses->num_rows > 0){
            while($row = $courses->fetch_assoc()){
                echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
            }
        } else {
            echo "<option value=''>No courses available</option>";
        }
        ?>
    </select>
    <br><br>
    <button type="submit" name="start_class">Start Live Class</button>
</form>
