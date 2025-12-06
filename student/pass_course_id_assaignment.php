<?php
session_start();
include '../dbconnection.php';

if(!isset($_SESSION['stulogEmail'])){
    header("Location: login.php");
    exit();
}

$stuEmail = $_SESSION['stulogEmail'];

$sql = "SELECT c.course_id, c.course_name
        FROM course_order o
        JOIN course c ON o.course_id = c.course_id
        WHERE o.stu_email = '$stuEmail'";
$result = $conn->query($sql);
?>

<!-- CSS Styling -->
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
.course-list {
    list-style: none;
    padding: 0;
}
.course-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    margin-bottom: 15px;
    background: #f9f9f9;
    border-left: 5px solid #2196F3;
    border-radius: 8px;
    transition: background 0.3s, transform 0.2s;
}
.course-item:hover {
    background: #e1f0ff;
    transform: translateY(-2px);
}
.course-name {
    font-size: 16px;
    color: #333;
    font-weight: bold;
}
.view-btn {
    text-decoration: none;
    background: #28a745;
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s;
}
.view-btn:hover {
    background: #218838;
}
.no-course {
    text-align: center;
    color: #555;
    font-style: italic;
    padding: 20px;
    background: #fff3cd;
    border-radius: 8px;
}
</style>

<div class="container">
<h3>Your Courses</h3>
<ul class="course-list">
<?php if($result && $result->num_rows > 0) { ?>
    <?php while($row = $result->fetch_assoc()) { ?>
        <li class="course-item">
            <span class="course-name"><?php echo htmlspecialchars($row['course_name']); ?></span>
            <a class="view-btn" 
               href="assaignments.php?course_id=<?php echo $row['course_id']; ?>">
                 View Assaignment
            </a>
        </li>
    <?php } ?>
<?php } else { ?>
    <li class="no-course">You are not enrolled in any course.</li>
<?php } ?>
</ul>
</div>
