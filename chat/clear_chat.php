<?php
session_start();
include "../dbconnection.php";

// Check for multiple session variable names (support different login methods)
$logged_in_email = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($logged_in_email) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die(json_encode(['status'=>0, 'message'=>'Please login first!']));
}

$u1 = $_POST['u1'];
$u2 = $_POST['u2'];

// Verify that u1 matches logged-in student
if($u1 !== $logged_in_email){
    die(json_encode(['status'=>0, 'message'=>'Unauthorized access!']));
}

$conn->query("DELETE FROM chat_messages WHERE (sender_email='$u1' AND receiver_email='$u2') 
OR (sender_email='$u2' AND receiver_email='$u1')");
echo json_encode(['status'=>1]);?>
