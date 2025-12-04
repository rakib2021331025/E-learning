<?php
session_start();
include '../dbconnection.php';
header('Content-Type: application/json');

// Handle Google Sign-In
if(isset($_POST['google_token']) && isset($_POST['google_email']) && isset($_POST['google_name'])){
    $google_email = $conn->real_escape_string($_POST['google_email']);
    $google_name = $conn->real_escape_string($_POST['google_name']);
    
    // Check if student exists
    $check_sql = "SELECT * FROM student WHERE stu_email = '$google_email'";
    $check_result = $conn->query($check_sql);
    
    if($check_result->num_rows > 0){
        // Student exists, just login
        $_SESSION['is_login'] = true;
        $_SESSION['stulogEmail'] = $google_email;
        $_SESSION['stu_email'] = $google_email;
        echo json_encode(['status' => 'login_success']);
    } else {
        // New student, register first
        $default_pass = 'google_' . bin2hex(random_bytes(8)); // Random password for Google users
        $insert_sql = "INSERT INTO student (stu_name, stu_email, stu_pass) VALUES ('$google_name', '$google_email', '$default_pass')";
        
        if($conn->query($insert_sql)){
            $_SESSION['is_login'] = true;
            $_SESSION['stulogEmail'] = $google_email;
            $_SESSION['stu_email'] = $google_email;
            echo json_encode(['status' => 'register_success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>

