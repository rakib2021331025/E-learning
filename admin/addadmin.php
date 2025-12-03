<?php
include_once('../dbconnection.php');
session_start();
header('Content-Type: application/json');

if (isset($_POST['adminemail']) && isset($_POST['adminpass'])) {
    // Escape input to prevent SQL Injection (basic way)
    $adminemail = $conn->real_escape_string($_POST['adminemail']);
    $adminpass = $conn->real_escape_string($_POST['adminpass']);

    // Check credentials in DB
    $sql = "SELECT admin_email FROM admin_login WHERE admin_email='$adminemail' AND admin_pass='$adminpass'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $_SESSION['loginstatus'] = true;
       // $_SESSION['admin_id'] = $row['admin_id'];

        $_SESSION['stuemail'] = $adminemail;
        echo json_encode(1);
    } else {
        echo json_encode(0);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid input']);
}
exit();/*

session_start();
include_once('../dbconnection.php');
header('Content-Type: application/json');

if (isset($_POST['adminemail']) && isset($_POST['adminpass'])) {

    $adminemail = $conn->real_escape_string($_POST['adminemail']);
    $adminpass = $conn->real_escape_string($_POST['adminpass']);

    // Check admin with ID and email
    $sql = "SELECT admin_id, admin_email 
            FROM admin_login 
            WHERE admin_email='$adminemail' AND admin_pass='$adminpass'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Set admin session
        $_SESSION['loginstatus'] = true;
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['adminemail'] = $row['admin_email'];

        echo json_encode(1);
    } else {
        echo json_encode(0);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid input']);
}
exit();*/
?>



