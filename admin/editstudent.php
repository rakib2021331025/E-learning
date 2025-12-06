<?php
include('./admininclude/header.php');
include('../dbconnection.php');

$stu_name = $stu_email = "";
$id = 0;

// Step 1: Get student ID from POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Step 2: Fetch student info
    $sql = "SELECT * FROM student WHERE stu_id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stu_name = $row['stu_name'];
        $stu_email = $row['stu_email'];
    } else {
        echo "<div class='alert alert-danger'>Invalid Student ID</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No Student Selected</div>";
    exit;
}

// Step 3: Update data if form submitted
if (isset($_POST['update'])) {
    $new_name = $conn->real_escape_string($_POST['stu_name']);
    $new_email = $conn->real_escape_string($_POST['stu_email']);

    // Validate email format in PHP (backup validation)
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Invalid Email Format</div>";
    } else {
        $update_sql = "UPDATE student SET stu_name = '$new_name', stu_email = '$new_email' WHERE stu_id = $id";

        if ($conn->query($update_sql) === TRUE) {
            header("Location: student.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to Update: " . $conn->error . "</div>";
        }
    }
}
?>

<!-- Step 4: Show Edit Form -->
<div class="container mt-5 col-md-6">
  <h3>Edit Student</h3>
  <form method="POST" onsubmit="return validateEmail()">
    <div class="mb-3">
      <label for="stu_name" class="form-label">Student Name</label>
      <input type="text" class="form-control" name="stu_name" id="stu_name" value="<?php echo htmlspecialchars($stu_name); ?>" required>
    </div>

    <div class="mb-3">
      <label for="stu_email" class="form-label">Student Email</label>
      <input type="text" class="form-control" name="stu_email" id="stu_email" value="<?php echo htmlspecialchars($stu_email); ?>" required>
    </div>

    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <button type="submit" name="update" class="btn btn-success">Update</button>
    <a href="student.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<!-- Email Validation Script -->
<script>
function validateEmail() {
    const email = document.getElementById("stu_email").value;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email)) {
        alert("Invalid email format! Example: example@gmail.com");
        return false;
    }
    return true;
}
</script>
