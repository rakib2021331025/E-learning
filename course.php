<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

      <style>
    body{
      padding-top:70px;
    }
    </style>


</head>
<body>
  <?php
  include('./dbconnection.php');
  include('./navbar.php');
  ?>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
  <?php
  // First 3 cards
  $sql = "SELECT * FROM course LIMIT 3";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $course_id = $row['course_id'];
      echo '
      <div class="col">
        <a href="coursedetail.php?course_id=' . $course_id . '" class="text-decoration-none text-dark">
          <div class="card h-100 shadow">
            <img src="' . str_replace('..', '.', $row['course_img']) . '" class="card-img-top" alt="Course Image">
            <div class="card-body">
              <h5 class="card-title">' . $row['course_name'] . '</h5>
              <p class="card-text">' . $row['course_desc'] . '</p>
              <p>
                <span class="old-price text-decoration-line-through">৳' . $row['course_orginal_price'] . '</span>
                <span class="offer-price fw-bold text-success ms-2">৳' . $row['course_price'] . '</span>
              </p>
              <button class="btn btn-primary">Enroll Now</button>
            </div>
            <div class="card-footer">
              <small class="text-muted">' . $row['course_duration'] . ' | Level: Beginner</small>
            </div>
          </div>
        </a>
      </div>';
    }
  } else {
    echo "<p class='text-white'>No courses available.</p>";
  }
  ?>
</div> <!-- Close first row here -->

<?php
// Then second 3 cards in a NEW row
$sql = "SELECT * FROM course LIMIT 3,3";
$result = $conn->query($sql);

echo '<div class="row row-cols-1 row-cols-md-3 g-4 mt-5">'; // Bootstrap row start

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $course_id = $row['course_id'];
    $imgPath = str_replace('..', '.', $row['course_img']); // Replace for display

    echo '
    <div class="col">
      <a href="coursedetail.php?course_id=' . $course_id . '" class="text-decoration-none text-dark">
        <div class="card h-100 shadow">
          <img src="' . $imgPath . '" class="card-img-top" alt="Course Image">
          <div class="card-body">
            <h5 class="card-title">' . $row['course_name'] . '</h5>
            <p class="card-text">' . $row['course_desc'] . '</p>
            <p>
              <span class="old-price text-decoration-line-through">৳ ' . $row['course_orginal_price'] . '</span>
              <span class="offer-price fw-bold text-success ms-2">৳ ' . $row['course_price'] . '</span>
            </p>
            <button class="btn btn-primary">Enroll Now</button>
          </div>
          <div class="card-footer">
            <small class="text-muted">' . $row['course_duration'] . ' | Level: Beginner</small>
          </div>
        </div>
      </a>
    </div>';
  }
} else {
  if (!$result) {
    echo "<p class='text-danger'>SQL Error: " . $conn->error . "</p>";
  } else {
    echo "<p class='text-white'>No courses available.</p>";
  }
}

echo '</div>'; // Close second row here
?> </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.min.js"></script>


</body>
</html>


