<?php
session_start();
include('./dbconnection.php');

// Handle redirect URL
$redirect_url = "index.php"; // default

if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
  $redirect_url = $_GET['redirect'];

  // If course_id and course_price passed, preserve them
  if (isset($_GET['course_id']) && isset($_GET['course_price'])) {
    $redirect_url .= "?course_id=" . $_GET['course_id'] . "&course_price=" . $_GET['course_price'];
  }
}

$msg = "";

// LOGIN CHECK
if (isset($_POST['login'])) {
  $email = $_POST['log_email'];
  $password = $_POST['log_password'];

  $sql = "SELECT * FROM student WHERE stu_email = '$email' AND stu_pass = '$password'";
  $result = $conn->query($sql);
  if ($result && $result->num_rows === 1) {
    $_SESSION['is_login'] = true;
    $_SESSION['stuLogEmail'] = $email;

    header("Location: $redirect_url");
    exit();
  } else {
    $msg = "<div class='alert alert-danger'>Invalid email or password!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
      background-color: #f8f9fa;
    }
    .form-box {
      max-width: 500px;
      margin: auto;
    }
  </style>
</head>
<body>

<?php include('./navbar.php'); ?>

<div class="container form-box">
  <h2 class="text-center mb-4">Login</h2>
  <?php echo $msg; ?>

  <!-- Login Form -->
  <form method="POST" class="border p-4 bg-white shadow" action="">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="log_email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="log_password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
