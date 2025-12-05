<?php
session_start();
include "../dbconnection.php";

// Check for multiple session variable names (support different login methods)
$logged_in_email = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($logged_in_email) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}

$student_email = $_POST['student_email'];

// Verify that student_email matches logged-in student
if($student_email !== $logged_in_email){
    die("Unauthorized access!");
}

$admin_email = "rakibhasan83012@gmail.com";

$sql="SELECT * FROM chat_messages 
      WHERE (sender_email='$student_email' AND receiver_email='$admin_email') 
         OR (sender_email='$admin_email' AND receiver_email='$student_email') 
      ORDER BY created_at ASC";
$q=$conn->query($sql);

while($row=$q->fetch_assoc()){
    $side = ($row['sender_email']==$student_email)?'student':'admin';
    echo "<div class='message $side'><b>".htmlspecialchars($row['sender_email'])."</b><br>".nl2br(htmlspecialchars($row['message']));

    if(!empty($row['file_path'])){
        $ext=strtolower(pathinfo($row['file_path'],PATHINFO_EXTENSION));
        $url=htmlspecialchars($row['file_path']);
        if(in_array($ext,['mp3','wav','ogg','webm','m4a'])){
            echo "<audio controls src='{$url}'></audio>";
        } else {
            echo "<a href='../{$url}' target='_blank'>📎 File</a>";
        }
    }
    echo "<div class='meta'>{$row['created_at']}</div></div><div style='clear:both'></div>";
}
?>