<?php
session_start();
date_default_timezone_set("Asia/Dhaka");
include "../dbconnection.php";

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    header("Location: ../loginorsignup.php");
    exit();
}

if(!isset($_GET['exam_id'])){
    die("<h3>No Exam Selected</h3>");
}

$exam_id = intval($_GET['exam_id']);

// Fetch exam
$exam_res = $conn->query("SELECT * FROM exams WHERE id=$exam_id");
if(!$exam_res || $exam_res->num_rows==0){
    die("<h3>Exam not found!</h3>");
}
$exam = $exam_res->fetch_assoc();

// Time check
$now = date("Y-m-d H:i:s");
if($now < $exam['start_time']) die("<h3>Exam has not started yet!</h3>");
if($now > $exam['end_time']) die("<h3>Exam time is over!</h3>");

// Fetch exam questions
$q_res = $conn->query("SELECT * FROM exam_questions WHERE id=$exam_id");
if(!$q_res || $q_res->num_rows==0){
    die("<h3>No questions found!</h3>");
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars($exam['title']); ?></title>
<style>
body { font-family: Arial, sans-serif; }
.question-block {
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 10px;
    background: #f9f9f9;
    border: 1px solid #ddd;
}
.q-image {
    max-width: 350px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
}
input[type="text"], textarea {
    width: 100%;
    padding: 8px;
    margin: 6px 0;
    box-sizing: border-box;
}
button {
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    background: #5cb85c;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
}
button:hover {
    background: #4cae4c;
}
</style>
</head>
<body>

<h2><?php echo htmlspecialchars($exam['title']); ?></h2>

<form method="POST" action="submit_exam.php" enctype="multipart/form-data">
<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">

<?php while($row = $q_res->fetch_assoc()){ ?>
<div class="question-block">
    <p><b><?php echo htmlspecialchars($row['question_text']); ?></b></p>

    <!-- Question Image -->
    <?php if(!empty($row['image_path'])){ ?>
        <img src="../admin/<?php echo htmlspecialchars($row['image_path']); ?>" class="q-image">
    <?php } ?>

    <?php 
    $name = "q".$row['id'];

    // MCQ
    if($row['question_type']=="mcq"){ ?>
        <label><input type="radio" name="<?php echo $name; ?>" value="A"> <?php echo htmlspecialchars($row['option_a']); ?></label><br>
        <label><input type="radio" name="<?php echo $name; ?>" value="B"> <?php echo htmlspecialchars($row['option_b']); ?></label><br>
        <label><input type="radio" name="<?php echo $name; ?>" value="C"> <?php echo htmlspecialchars($row['option_c']); ?></label><br>
        <label><input type="radio" name="<?php echo $name; ?>" value="D"> <?php echo htmlspecialchars($row['option_d']); ?></label><br>

    <?php } 
    // Short Answer
    elseif($row['question_type']=="short"){ ?>
        <input type="text" name="<?php echo $name; ?>" placeholder="Write your answer..." required>

    <?php } 
    // Long Answer
    elseif($row['question_type']=="long"){ ?>
        <textarea name="<?php echo $name; ?>" rows="4" placeholder="Write your answer..." required></textarea>

    <?php } 
    // File upload answer (image/pdf)
    elseif($row['question_type']=="written"){ ?>
        <p>Upload your answer (Image/PDF):</p>
        <input type="file" name="<?php echo $name; ?>" accept="image/*,application/pdf" required>
    <?php } 
    // Default text input
    else { ?>
        <input type="text" name="<?php echo $name; ?>" placeholder="Write your answer..." required>
    <?php } ?>

</div>
<?php } ?>

<button type="submit">Submit Exam</button>
</form>

</body>
</html>
