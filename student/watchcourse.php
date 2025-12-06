<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Watch Course</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    body { margin: 0; padding: 0; font-family: sans-serif; }
    .container-fluid { padding: 0 20px; }
    .border-right { border-right: 1px solid #ccc; }
    video { width: 100%; height: auto; }
    /* Active lesson highlight */
    #playlist li.active {
      background-color: #d4edda;
      font-weight: bold;
    }
    #playlist li {
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="container-fluid bg-success p-3 text-white d-flex justify-content-between align-items-center">
    <h3 class="mb-0">Welcome to E-Learning</h3>
    <a href="./mycourse.php" class="btn btn-danger">My Courses</a>
  </div>

  <!-- Main Content -->
  <div class="container-fluid mt-4">
    <div class="row">
      
      <!-- Sidebar Lessons -->
      <div class="col-sm-3 border-right">
        <h4 class="text-center">Lessons</h4>
        <ul id="playlist" class="nav flex-column">
          <?php 
          if (isset($_GET['course_id'])) {
              $course_id = $_GET['course_id'];
              $sql = "SELECT * FROM lesson WHERE course_id='$course_id'";
              $result = $conn->query($sql);
              if ($result && $result->num_rows > 0) {
                  $basePath = '';  
                  while ($row = $result->fetch_assoc()) {
                      
                      $videoUrl = $basePath . $row['lesson_link'];
                      echo '<li class="nav-item border-bottom py-2" movieurl="' . htmlspecialchars($videoUrl, ENT_QUOTES) . '">' . htmlspecialchars($row['lesson_name']) . '</li>';
                  }
              } else {
                  echo '<li class="nav-item py-2">No lessons found.</li>';
              }
          } else {
              echo '<li class="nav-item py-2">Course ID not provided.</li>';
          }
          ?>
        </ul>
      </div>

      <!-- Video Area -->
      <div class="col-sm-9">
        <video id="videoarea" class="mt-3" controls autoplay muted preload="metadata">
          <source src="" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>

    </div>
  </div>

  <!-- jQuery & Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function () {
      const firstLesson = $("#playlist li").eq(0);
      if (firstLesson.length) {
        const firstURL = firstLesson.attr("movieurl");
        console.log("Initial video URL:", firstURL);
        $("#videoarea source").attr("src", firstURL);
        $("#videoarea")[0].load();
        firstLesson.addClass("active");
      }

      $("#playlist li").on("click", function () {
        const movieurl = $(this).attr("movieurl");
        if ($("#videoarea source").attr("src") !== movieurl) {
          console.log("Switching to video:", movieurl);
          $("#videoarea source").attr("src", movieurl);
          $("#videoarea")[0].load();

          $("#playlist li").removeClass("active");
          $(this).addClass("active");
        }
      });
    });
  </script>
</body>
</html>
