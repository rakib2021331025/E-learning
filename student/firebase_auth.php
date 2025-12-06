<?php
session_start();
include '../dbconnection.php';
header('Content-Type: application/json');

if (!isset($_POST['firebase_email']) || empty($_POST['firebase_email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Email is required']);
    exit();
}

$email = $_POST['firebase_email'];
$name = $_POST['firebase_name'] ?? '';
$photo = $_POST['firebase_photo'] ?? '';
$uid = $_POST['firebase_uid'] ?? '';

// Check if student exists
$check_sql = "SELECT * FROM student WHERE stu_email = '$email'";
$result = $conn->query($check_sql);

if ($result && $result->num_rows > 0) {
    // Student exists - Login
    $_SESSION['is_login'] = true;
    $_SESSION['stulogEmail'] = $email;
    $_SESSION['stu_email'] = $email;
    $_SESSION['stuName'] = $name;
    if (!empty($photo)) {
        $_SESSION['stu_img'] = $photo;
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'redirect' => '../index.php'
    ]);
} else {
    // Student doesn't exist - Register
    if (empty($name)) {
        $name = explode('@', $email)[0]; // Use email prefix as name
    }
    
    // Generate a random password (Firebase handles auth, but we need a password for DB)
    $random_password = bin2hex(random_bytes(8));
    
    $insert_sql = "INSERT INTO student (stu_name, stu_email, stu_pass, stu_img) 
                   VALUES ('$name', '$email', '$random_password', '$photo')";
    
    if ($conn->query($insert_sql)) {
        $_SESSION['is_login'] = true;
        $_SESSION['stulogEmail'] = $email;
        $_SESSION['stu_email'] = $email;
        $_SESSION['stuName'] = $name;
        if (!empty($photo)) {
            $_SESSION['stu_img'] = $photo;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Registration successful',
            'redirect' => '../index.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Registration failed: ' . $conn->error
        ]);
    }
}

