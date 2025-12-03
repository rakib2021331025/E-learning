

<?php
include('./admininclude/header.php');
include('../dbconnection.php');

// initialize
$course_name = $course_desc = $course_author = $course_duration = "";
$course_price = $course_orginal_price = $course_img = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_desc = $_POST['course_desc'];
    $course_author = $_POST['course_author'];
    $course_duration = $_POST['course_duration'];
    $course_price = $_POST['course_price'];
    $course_orginal_price = $_POST['course_org_price'];

    $course_img = $_FILES['course_img']['name'];
    $course_img_temp = $_FILES['course_img']['tmp_name'];
    $img_folder = '../image/courseimg/' . $course_img;
    move_uploaded_file($course_img_temp, $img_folder);

    //  Prepared Statement
    $stmt = $conn->prepare("INSERT INTO course (course_name, course_desc, course_author, course_img, course_duration, course_price, course_orginal_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $course_name, $course_desc, $course_author, $img_folder, $course_duration, $course_price, $course_orginal_price);

    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success col-sm-6">Course added Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger col-sm-6">Course upload Failed: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}
?>


<div class="container mt-5" style="margin-left:260px;width:689px;"> <!-- Adjust this if your sidebar is wider -->
  <div class="card p-4 shadow"style="background-color:#256789;color:white;">
    <h4 class="text-center mb-4">Add New Course</h4>
    
    <form action="" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="course_name" class="form-label">Course Name</label>
        <input type="text" class="form-control" id="course_name" name="course_name" required>
      </div>

      <div class="mb-3">
        <label for="course_desc" class="form-label">Course Description</label>
        <textarea class="form-control" id="course_desc" name="course_desc" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="course_author" class="form-label">Author</label>
        <input type="text" class="form-control" id="course_author" name="course_author" required>
      </div>

      <div class="mb-3">
        <label for="course_duration" class="form-label">Course Duration</label>
        <input type="text" class="form-control" id="course_duration" name="course_duration">
      </div>

      <div class="mb-3">
        <label for="course_price" class="form-label">Course Price</label>
        <input type="text" class="form-control" id="course_price" name="course_price">
      </div>

      <div class="mb-3">
        <label for="course_org_price" class="form-label">Course Original Price</label>
        <input type="text" class="form-control" id="course_org_price" name="course_org_price">
      </div>

      <div class="mb-3">
        <label for="course_img" class="form-label">Upload Course Image</label>
        <input class="form-control" type="file" id="course_img" name="course_img" accept="image/*" onchange="previewImage(event)">
      </div>

      <div class="mb-3">
        <img id="img_preview" src="#" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; display: none;">
      </div>

      <div class="d-flex justify-content-start gap-2">
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="reset" class="btn btn-secondary" onclick="clearImagePreview()">Close</button>
      </div>
      <?php 
      if(isset($msg)) echo $msg;?>
  
    </form>
  </div>
</div>

<!-- JS for Image Preview -->
<script>
  function previewImage(event) {
    const preview = document.getElementById('img_preview');
    const file = event.target.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    }
  }

  function clearImagePreview() {
    const preview = document.getElementById('img_preview');
    preview.src = '#';
    preview.style.display = 'none';
  }
</script>
