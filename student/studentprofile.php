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
  $stuName = $row["stu_name"];
  $stuOcc = $row["stu_occ"];
  $stuImg = $row["stu_img"];
  $_SESSION['stu_img'] = $stuImg;
}

// If Update button is clicked
if (isset($_POST['updateStuNameBtn'])) {
  if (empty(trim($_POST['stuName']))) {
    $passmsg = '<div class="alert alert-warning mt-2" role="alert">Please fill all fields.</div>';
  } else {
    $stuName = $_POST["stuName"];
    $stuOcc = $_POST["stuOcc"];

    if (!empty($_FILES['stuImg']['name'])) {
      $stu_image = $_FILES['stuImg']['name'];
      $stu_image_temp = $_FILES['stuImg']['tmp_name'];
      $img_folder = '../image/stu/' . $stu_image;
      move_uploaded_file($stu_image_temp, $img_folder);
    } else {
      $img_folder = $stuImg; // Keep old image if new one not uploaded
    }

    $sql = "UPDATE student SET stu_name='$stuName', stu_occ='$stuOcc', stu_img='$img_folder' WHERE stu_email='$stuEmail'";
    if ($conn->query($sql) === TRUE) {
      $passmsg = '<div class="alert alert-success mt-2" role="alert">Profile updated successfully.</div>';
      $stuImg = $img_folder;
      $_SESSION['stu_img'] = $stuImg;
    } else {
      $passmsg = '<div class="alert alert-danger mt-2" role="alert">Unable to update profile.</div>';
    }
  }
}
?>

<!-- Form Area -->
<div class="col-sm-8 offset-sm-1 mt-3 d-flex justify-content-center">
  <form method="POST" enctype="multipart/form-data" class="p-4 shadow bg-white rounded w-75">
    <h3 class="text-center mb-4">Update Profile</h3>

    <div class="form-group">
      <label for="stuId">Student ID</label>
      <input type="text" class="form-control" id="stuId" name="stuId" value="<?php echo htmlspecialchars($stuId); ?>" readonly>
    </div>

    <div class="form-group mt-3">
      <label for="stuEmail">Email</label>
      <input type="text" class="form-control" id="stuEmail" name="stuEmail" value="<?php echo htmlspecialchars($stuEmail); ?>" readonly>
    </div>

    <div class="form-group mt-3">
      <label for="stuName">Name</label>
      <input type="text" class="form-control" id="stuName" name="stuName" value="<?php echo htmlspecialchars($stuName); ?>">
    </div>

    <div class="form-group mt-3">
      <label for="stuOcc">Occupation</label>
      <input type="text" class="form-control" id="stuOcc" name="stuOcc" value="<?php echo htmlspecialchars($stuOcc); ?>">
    </div>

    <div class="form-group mt-3">
      <label for="stuImg">Upload Image</label>
      <input type="file" class="form-control" id="stuImg" name="stuImg" accept="image/*">
      <?php if (!empty($stuImg)) {
        echo '<img src="' . htmlspecialchars($stuImg) . '" class="profile-img-preview mt-2" alt="Profile Image" style="width:100px;height:100px;">';
      } ?>
    </div>

    <button type="submit" class="btn btn-primary mt-3" name="updateStuNameBtn">Update</button>
    <?php if (!empty($passmsg)) echo $passmsg; ?>
  </form>
</div>

<script>
  document.getElementById('stuImg').addEventListener('change', function (event) {
    const [file] = this.files;
    if (file) {
      const imgPreview = document.querySelector('.profile-img-preview');
      if (imgPreview) {
        imgPreview.src = URL.createObjectURL(file);
      }
    }
  });
</script>
