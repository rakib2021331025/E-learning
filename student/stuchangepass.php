<?php
include('../dbconnection.php');
include('./studentinclude/header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$msg = "";
$stu_email = $_SESSION['stulogEmail'] ?? ""; // Session theke email
echo $stu_email;

if (isset($_POST['change'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Step 1: fetch student password
    $sql = "SELECT stu_pass FROM student WHERE stu_email = '$stu_email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $db_pass = $row['stu_pass'];

        if ($db_pass === $old_pass) { // Or use password_verify if hashing
            if ($new_pass === $confirm_pass) {
                // Step 2: update password in 'student' table
                $update_sql = "UPDATE student SET stu_pass = '$new_pass' WHERE stu_email = '$stu_email'";
                if ($conn->query($update_sql) === TRUE) {
                    $msg = '<div class="alert alert-success">Password changed successfully.</div>';
                } else {
                    $msg = '<div class="alert alert-danger">Failed to update password.</div>';
                }
            } else {
                $msg = '<div class="alert alert-warning">New passwords do not match.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Old password is incorrect.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Student not found.</div>';
    }
}
?>

<!-- ✅ HTML Form -->
<div class="container mt-5 col-md-6">
    <h3>Change Your Password</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="old_pass" class="form-label">Old Password</label>
            <input type="password" class="form-control" id="old_pass" name="old_pass" required>
        </div>

        <div class="mb-3">
            <label for="new_pass" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_pass" name="new_pass" required>
        </div>

        <div class="mb-3">
            <label for="confirm_pass" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" required>
        </div>

        <button type="submit" name="change" class="btn btn-success">Change Password</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>

        <div class="mt-3">
            <?php if (!empty($msg)) echo $msg; ?>
        </div>
    </form>
</div>
