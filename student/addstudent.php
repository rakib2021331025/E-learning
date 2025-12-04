<?php
if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');
include('../dbconnection.php');

// 1. Email Exists Check
if (!empty($_POST['stuemail']) && empty($_POST['stuname']) && empty($_POST['stupass'])) {
    $email = $_POST['stuemail'];
    $sql = "SELECT stu_email FROM student WHERE stu_email = '$email'";
    $result = $conn->query($sql);
    echo json_encode($result->num_rows);
    exit();
}

// 2. Registration
if (!empty($_POST['stuname']) && !empty($_POST['stuemail']) && !empty($_POST['stupass'])) {
    $name = $_POST['stuname'];
    $email = $_POST['stuemail'];
    $pass = $_POST['stupass'];

    // Email already exists check
    $check = "SELECT stu_email FROM student WHERE stu_email = '$email'";
    $res = $conn->query($check);
    if ($res->num_rows > 0) {
        echo json_encode("EmailExists");
        exit();
    }

    $sql = "INSERT INTO student (stu_name, stu_email, stu_pass) VALUES ('$name', '$email', '$pass')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode("OK");
    } else {
        echo json_encode("Error");
    }
    exit();
}

// 3. Login
if (!empty($_POST['stulogemail']) && !empty($_POST['stulogpass'])) {
    $stulogEmail = $_POST['stulogemail'];
    $stulogPass = $_POST['stulogpass'];

    $sql = "SELECT * FROM student WHERE stu_email = '$stulogEmail' AND stu_pass = '$stulogPass'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['is_login'] = true;
        $_SESSION['stulogEmail'] = $stulogEmail;
         $_SESSION['stu_email'] = $stulogEmail; // ✅ এটা add করো
        echo json_encode(1); // Success
    } else {
        echo json_encode(0); // Invalid
    }
    exit();
}

// Default fallback
echo json_encode("Invalid Request");
exit();
?>
