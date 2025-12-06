<?php
include('./dbconnection.php');
include('./navbar.php');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
  echo "<script> location.href='courses.php'; </script>";
  exit();
}

$course_id = $_GET['course_id'];
$sql = "SELECT * FROM course WHERE course_id = '$course_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows == 1) {
  $row = $result->fetch_assoc();
} else {
  echo "<div class='alert alert-danger'>Course not found.</div>";
  exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $row['course_name']; ?> - Course Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
    }
    .course-img {
      max-height: 400px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-5">
      <img src="<?php echo str_replace('..', '.', $row['course_img']); ?>" class="img-fluid course-img" alt="Course Image">
    </div>
    <div class="col-md-7">
      <h3><?php echo $row['course_name']; ?></h3>
      <p><?php echo $row['course_desc']; ?></p>
      <p>
        <span class="text-muted text-decoration-line-through">$<?php echo $row['course_orginal_price']; ?></span>
        <span class="text-success fw-bold ms-2">$<?php echo $row['course_price']; ?></span>
      </p>
      <p><strong>Duration:</strong> <?php echo $row['course_duration']; ?></p>
      <p><strong>Level:</strong> Beginner</p>

      <?php
if (isset($_SESSION['is_login'])) {
  echo '<a href="checkout.php?course_id=' . $row['course_id'] . '&course_price=' . $row['course_price'] . '" class="btn btn-success">Buy Now</a>';
} else {
  echo '<a href="loginorsignup.php?redirect=checkout.php&course_id=' . $row['course_id'] . '&course_price=' . $row['course_price'] . '" class="btn btn-primary">Login to Buy</a>';
}?>


  </div>

  <hr class="my-5">

  <div class="mt-4">
    <h4>Lessons</h4>
    <?php
    $lesson_sql = "SELECT * FROM lesson WHERE course_id = '$course_id'";
    $lesson_result = $conn->query($lesson_sql);
    $count = 1;

    if ($lesson_result && $lesson_result->num_rows > 0) {
      echo '
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Lesson Name</th>
              <th>Lesson ID</th>
            </tr>
          </thead>
          <tbody>';
      while ($lesson = $lesson_result->fetch_assoc()) {
        echo '
            <tr>
              <td>' . $count . '</td>
              <td>' . htmlspecialchars($lesson['lesson_name']) . '</td>
              <td>' . $lesson['lesson_id'] . '</td>
            </tr>';
        $count++;
      }
      echo '
          </tbody>
        </table>
      </div>';
    } else {
      echo "<p class='text-muted'>No lessons available for this course.</p>";
    }
    ?>
  </div>
    </body></html>