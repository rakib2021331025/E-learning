<?php
include('./admininclude/header.php');
include('../dbconnection.php');

$course_name = $course_author = "";
$id = 0;

// Step 1: Get course ID from POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Step 2: Fetch course info
    $sql = "SELECT * FROM course WHERE course_id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $course_name = $row['course_name'];
        $course_author = $row['course_author'];
    } else {
        echo "<div class='alert alert-danger'>Invalid Course ID</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No Course Selected</div>";
    exit;
}

// Step 3: Update data if form submitted
if (isset($_POST['update'])) {
    $new_name = $_POST['course_name'];
    $new_author = $_POST['course_author'];

    $update_sql = "UPDATE course SET course_name = '$new_name', course_author = '$new_author' WHERE course_id = $id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<div class='alert alert-success'>Course Updated Successfully</div>";
      //  echo '<meta http-equiv="refresh" content="2;URL=cources.php">';
              header("Location: cources.php");
      //  exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to Update</div>";
    }
}
?>

<!-- Step 4: Show Edit Form -->
<div class="container mt-5 col-md-6">
  <h3>Edit Course</h3>
  <form method="POST">
    <div class="mb-3">
      <label for="course_name" class="form-label">Course Name</label>
      <input type="text" class="form-control" name="course_name" value="<?php echo htmlspecialchars($course_name); ?>" required>
    </div>

    <div class="mb-3">
      <label for="course_author" class="form-label">Course Author</label>
      <input type="text" class="form-control" name="course_author" value="<?php echo htmlspecialchars($course_author); ?>" required>
    </div>

    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <button type="submit" name="update" class="btn btn-success">Update</button>
    <a href="course.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
