<?php
include('./admininclude/header.php');
include('../dbconnection.php');

$msg = "";
$lesson_name = $lesson_desc = $lesson_link = "";
$course_id = "";

// Check if lesson_id is present in URL
if (isset($_GET['lesson_id']) && !empty($_GET['lesson_id'])) {
    $lesson_id = intval($_GET['lesson_id']);
    $sql = "SELECT * FROM lesson WHERE lesson_id = $lesson_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $lesson_name = $row['lesson_name'] ?? '';
        $lesson_desc = $row['lesson_desc'] ?? '';
        $lesson_link = $row['lesson_link'] ?? '';
        $course_id = $row['course_id'] ?? 0;
    } else {
        echo "<script>alert('Lesson not found'); window.location='lessonlist.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Lesson ID missing'); window.location='lessonlist.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_name = $_POST['lesson_name'] ?? '';
    $lesson_desc = $_POST['lesson_desc'] ?? '';
    $course_id = intval($_POST['course_id'] ?? 0);

    // Handle new video upload if available
    if (isset($_FILES['lesson_video']) && !empty($_FILES['lesson_video']['name'])) {
        $video_name = basename($_FILES['lesson_video']['name']);
        $video_tmp = $_FILES['lesson_video']['tmp_name'];
        $video_folder = '../lessonvid/' . $video_name;

        if (move_uploaded_file($video_tmp, $video_folder)) {
            $lesson_link = $video_folder;
        } else {
            $msg = '<div class="alert alert-danger col-sm-6">Video upload failed.</div>';
        }
    }

    $sql_update = "UPDATE lesson SET 
        lesson_name = '$lesson_name',
        lesson_desc = '$lesson_desc',
        lesson_link = '$lesson_link',
        course_id = $course_id
        WHERE lesson_id = $lesson_id";

    if ($conn->query($sql_update) === TRUE) {
        $msg = '<div class="alert alert-success col-sm-6">Lesson updated successfully.</div>';
    } else {
        $msg = '<div class="alert alert-danger col-sm-6">Update failed: ' . $conn->error . '</div>';
    }
}
?>

<div class="container mt-5" style="margin-left:250px;width:700px;">
  <div class="card p-4 shadow" style="background-color:#256789; color:white;">
    <h4 class="text-center mb-4">Edit Lesson</h4>

    <form action="" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="lesson_name" class="form-label">Lesson Name</label>
        <input type="text" class="form-control" id="lesson_name" name="lesson_name" required value="<?php echo htmlspecialchars($lesson_name); ?>">
      </div>

      <div class="mb-3">
        <label for="lesson_desc" class="form-label">Lesson Description</label>
        <textarea class="form-control" id="lesson_desc" name="lesson_desc" rows="3" required><?php echo htmlspecialchars($lesson_desc); ?></textarea>
      </div>

      <div class="mb-3">
        <label for="course_id" class="form-label">Course ID</label>
        <input type="number" class="form-control" id="course_id" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>" required>
      </div>

      <div class="mb-3">
        <label for="lesson_video" class="form-label">Upload Lesson Video (Leave blank to keep existing)</label>
        <input class="form-control" type="file" id="lesson_video" name="lesson_video" accept="video/*" onchange="previewVideo(event)">
      </div>

  <div class="mb-3" id="video_preview_container">
        <?php 
        if (!empty($lesson_link) && file_exists($lesson_link)) {
            $video_path_for_html = str_replace('../', '', $lesson_link);
            echo '<video width="320" height="240" controls>
                    <source src="/Elearning/' . $video_path_for_html . '" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>';
        } else {
            echo "<p>No video uploaded yet.</p>";
        }
        ?>
      </div>
      <div class="d-flex justify-content-start gap-2">
        <button type="submit" class="btn btn-success">Update Lesson</button>
        <button type="reset" class="btn btn-secondary" onclick="clearVideoPreview()">Reset</button>
      </div>

      <?php if ($msg != "") echo $msg; ?>
    </form>
  </div>
</div>

<script>
  function previewVideo(event) {
    const previewContainer = document.getElementById('video_preview_container');
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      const url = URL.createObjectURL(file);
      previewContainer.innerHTML = '';

      const video = document.createElement('video');
      video.width = 320;
      video.height = 240;
      video.controls = true;
      video.src = url;
      previewContainer.appendChild(video);
    }
  }

  function clearVideoPreview() {
    const previewContainer = document.getElementById('video_preview_container');
    previewContainer.innerHTML = '';
  }
</script>
