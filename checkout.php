<?php
include('./dbconnection.php');
include('./navbar.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// যদি লগইন না করে থাকে
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo "<script>location.href='loginorsignup.php';</script>";
    exit();
}

// GET দিয়ে course_id ও course_price না এলে courses.php তে রিডাইরেক্ট করো
if (!isset($_GET['course_id']) || !isset($_GET['course_price'])) {
    echo "<script>location.href='courses.php';</script>";
    exit();
}

$course_id = $_GET['course_id'];
$course_price = $_GET['course_price'];

$student_email = $_SESSION['stuLogEmail'] ?? '';
$student_name = $_SESSION['stuName'] ?? '';

// যদি stuName না থাকে, তাহলে DB থেকে আনি
if (empty($student_name) && !empty($student_email)) {
    $stmt = $conn->prepare("SELECT stu_name FROM student WHERE stu_email = ?");
    $stmt->bind_param("s", $student_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $student_name = $row['stu_name'];
        $_SESSION['stuName'] = $student_name;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - iSchool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="padding-top:70px;">

<div class="container">
    <h2 class="mb-4">Course Payment</h2>
    <form action="aftercheckout.php" method="POST">
        <div class="mb-3">
            <label for="studentName" class="form-label">Student Name</label>
            <input type="text"  class="form-control" name="studentName" id="studentName" value="<?php echo htmlspecialchars($student_name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="studentEmail" class="form-label">Student Email</label>
            <input type="email"  class="form-control" name="studentEmail" id="studentEmail" value="<?php echo htmlspecialchars($student_email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="courseId" class="form-label">Course ID</label>
            <input type="text" readonly class="form-control" name="courseId" id="courseId" value="<?php echo htmlspecialchars($course_id); ?>" required>
        </div>
        <div class="mb-3">
            <label for="coursePrice" class="form-label">Course Price (BDT)</label>
            <input type="text" readonly class="form-control" name="coursePrice" id="coursePrice" value="<?php echo htmlspecialchars($course_price); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</div>

</body>
</html>
