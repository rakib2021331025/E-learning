<?php
session_start();

include('./admininclude/header.php');
include('../dbconnection.php');


$lesson_name = $lesson_desc = $course_id = $course_name = "";
$lesson_link = $lesson_link_temp = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_name = $_POST['lesson_name']??'';
    $lesson_desc = $_POST['lesson_desc']??'';
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name']??'';

    // Upload video
    $lesson_link = $_FILES['lesson_link']['name'];
    $lesson_link_temp = $_FILES['lesson_link']['tmp_name'];
    
    // Create lessonvid directory if it doesn't exist
    $link_folder = '../lessonvid/';
    if (!is_dir($link_folder)) {
        mkdir($link_folder, 0777, true);
    }
    
    // Generate unique filename to prevent conflicts
    $file_extension = pathinfo($lesson_link, PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $file_extension;
    $target_path = $link_folder . $unique_filename;

    if (move_uploaded_file($lesson_link_temp, $target_path)) {
        // Store the relative path in database
        $relative_path = 'lessonvid/' . $unique_filename;
        
        $sql = "INSERT INTO lesson 
                (lesson_name, lesson_desc, lesson_link, course_id, course_name) 
                VALUES 
                ('$lesson_name', '$lesson_desc', '$relative_path', '$course_id', '$course_name')";

        if ($conn->query($sql) === TRUE) {
            $msg = '<div class="alert alert-success col-sm-6 mt-3">Lesson added successfully</div>';
        } else {
            $msg = '<div class="alert alert-danger col-sm-6 mt-3">Insert Failed: ' . $conn->error . '</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger col-sm-6 mt-3">Video upload failed</div>';
    }
}
?>

<div class="container mt-5" style="margin-left:250px; width:700px;">
  <div class="card p-4 shadow" style="background-color:#256789; color:white;">
    <h4 class="text-center mb-4">Add New Lesson</h4>
    
    <form action="" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="course_id" class="form-label">Course ID</label>
        <input type="text" class="form-control" id="course_id" name="course_id" required>
      </div>

      <div class="mb-3">
        <label for="course_name" class="form-label">Course Name</label>
        <input type="text" class="form-control" id="course_name" name="course_name" required>
      </div>

      <div class="mb-3">
        <label for="lesson_name" class="form-label">Lesson Name</label>
        <input type="text" class="form-control" id="lesson_name" name="lesson_name" required>
      </div>

      <div class="mb-3">
        <label for="lesson_desc" class="form-label">Lesson Description</label>
        <textarea class="form-control" id="lesson_desc" name="lesson_desc" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="lesson_link" class="form-label">Upload Lesson Video</label>
        <input class="form-control" type="file" id="lesson_link" name="lesson_link" accept="video/*" required>
      </div>

      <div class="d-flex justify-content-start gap-2">
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>

      <?php if (!empty($msg)) echo $msg; ?>
    </form>
  </div>
</div>