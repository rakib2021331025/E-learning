<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once('../dbconnection.php'); // DB connection

if (!isset($_SESSION['is_login']) && isset($_POST['checkLogEmail'])) {
    $stuLogEmail = $_POST['stuLogEmail'];
    $stuLogPass = $_POST['stuLogPass'];

    $sql = "SELECT stu_email, stu_pass FROM student WHERE stu_email = '$stuLogEmail' AND stu_pass = '$stuLogPass'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['is_login'] = true;
        $_SESSION['stuLogEmail'] = $stuLogEmail;
        echo json_encode(['status' => 1]);
    } else {
        echo json_encode(['status' => 0]);
    }
}
?>
