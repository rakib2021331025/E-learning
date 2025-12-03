<?php
include('../dbconnection.php');
include('./admininclude/header.php');

$msg = "";

if (isset($_POST['change'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    $admin_email = $_POST['admin_email']; // এখানে hidden input থেকে ইমেইল আসবে

    // Step 1: check old password match
    $sql = "SELECT admin_pass FROM admin WHERE admin_email = '$admin_email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $db_pass = $row['admin_pass'];

        if ($db_pass === $old_pass) {
            if ($new_pass === $confirm_pass) {
                // Step 2: update password
                $update_sql = "UPDATE admin SET admin_pass = '$new_pass' WHERE admin_email = '$admin_email'";
                if ($conn->query($update_sql) === TRUE) {
                    $msg = '<div class="alert alert-success">Password changed successfully</div>';
                } else {
                    $msg = '<div class="alert alert-danger">Failed to update password</div>';
                }
            } else {
                $msg = '<div class="alert alert-warning">New passwords do not match</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Old password is incorrect</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Admin not found</div>';
        
    }
}
?>

<!-- ✅ HTML Form -->
<div class="container mt-5 col-md-6" >
    <h3>Change Admin Password</h3>
    <form method="POST"style="width:400px;">
        <!-- Hidden Email Field -->
        <input type="hidden" name="admin_email" value="rakibhasan83012@gmail.com">
        <!-- তুমি এখানে admin এর ইমেইল manually বসাতে পারো অথবা dynamically -->

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

        <div class="mt-3"><?php if (!empty($msg)) echo $msg; ?></div>
    </form>
</div>
