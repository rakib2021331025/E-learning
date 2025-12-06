<?php
include('./studentinclude/header.php');
include('../dbconnection.php');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['is_login'])) {
  echo "<script> location.href='../index.php'; </script>";
  exit();
}

$stuId = $stuName = $stuEmail = $stuOcc = $stuImg = "";
$passmsg = "";

// Get student email from session and sync for sidebar
if (isset($_SESSION['stulogEmail'])) {
  $stuEmail = $_SESSION['stulogEmail'];
  $_SESSION['stulogEmail'] = $stuEmail;
}

// Fetch student info from DB
$sql = "SELECT * FROM student WHERE stu_email='$stuEmail'";
$result = $conn->query($sql);
if ($result && $result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $stuId = $row["stu_id"];
}

// If Submit button is clicked
if (isset($_POST['submitFeedbackBtn'])) {
  if (empty(trim($_POST['f_content']))) {
    $passmsg = '<div class="alert alert-warning mt-2" role="alert">Please fill all fields.</div>';
  } else {
    $fcontent = $_POST["f_content"];

    // Fix: properly insert values
    $sql = "INSERT INTO feedback (f_content, stu_id) VALUES ('$fcontent', '$stuId')";
    if ($conn->query($sql) === TRUE) {
      $passmsg = '<div class="alert alert-success mt-2" role="alert">Feedback submitted successfully.</div>';
    } else {
      $passmsg = '<div class="alert alert-danger mt-2" role="alert">Unable to submit feedback.</div>';
    }
  }
}
?>


<div class="col-sm-8 offset-sm-1 mt-3 d-flex justify-content-center">
  <form method="POST" class="p-4 shadow bg-white rounded w-75">
    <h3 class="text-center mb-4">Write Feedback</h3>

    <div class="form-group">
      <label for="stuId">Student ID</label>
      <input type="text" class="form-control" id="stuId" name="stuId" value="<?php echo htmlspecialchars($stuId); ?>" >
    </div>

    <div class="form-group mt-3">
      <label for="f_content">Your Feedback</label>
      <textarea class="form-control" id="f_content" name="f_content" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary mt-3" name="submitFeedbackBtn">Submit</button>
    <?php if (!empty($passmsg)) echo $passmsg; ?>
  </form>
</div>
