<?php
session_start();
include "../dbconnection.php";
$adminEmail='rakibhasan83012@gmail.com';
// Admin login check (adjust your session)
if(!isset($_SESSION['adminEmail'])){
    header("Location: addadmin.php");
    exit();
}

// Check exam_id and student_email
if(!isset($_GET['exam_id']) || !isset($_GET['student'])){
    die("Invalid request!");
}

$exam_id = intval($_GET['exam_id']);
$student_email = $_GET['student'];

// Fetch exam title
$exam_res = $conn->query("SELECT * FROM exams WHERE id=$exam_id");
if(!$exam_res || $exam_res->num_rows==0){
    die("Exam not found!");
}
$exam = $exam_res->fetch_assoc();

// Fetch student's answers
$ans_res = $conn->query("
    SELECT ea.*, eq.question_text, eq.question_type, eq.option_a, eq.option_b, eq.option_c, eq.option_d, eq.image_path
    FROM exam_answers ea
    JOIN exam_questions eq ON ea.question_id = eq.id
    WHERE ea.exam_id=$exam_id AND ea.student_email='$student_email'
");
if(!$ans_res || $ans_res->num_rows==0){
    die("No answers found for this student!");
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Evaluate Exam - <?php echo htmlspecialchars($exam['title']); ?></title>
<style>
.question-block { padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; background:#f9f9f9; }
.q-image { max-width:300px; margin:10px 0; }
input[type=number] { width:80px; padding:5px; margin-top:5px; }
button { padding:8px 20px; font-size:15px; cursor:pointer; background:#5cb85c; color:white; border:none; border-radius:5px; }
button:hover { background:#4cae4c; }
</style>
</head>
<body>

<h2>Evaluate Exam: <?php echo htmlspecialchars($exam['title']); ?></h2>
<h3>Student: <?php echo htmlspecialchars($student_email); ?></h3>

<form method="POST" action="save_marks.php">
<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
<input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">

<?php while($row = $ans_res->fetch_assoc()){ ?>
<div class="question-block">
    <p><b>Question:</b> <?php echo htmlspecialchars($row['question_text']); ?></p>

    <!-- Question Image -->
    <?php if(!empty($row['image_path'])){ ?>
        <img src="../admin/<?php echo $row['image_path']; ?>" class="q-image">
    <?php } ?>

    <!-- Student Answer -->
    <p><b>Student Answer:</b></p>
    <?php if($row['question_type']=='file'){ 
        $ext = pathinfo($row['answer_text'], PATHINFO_EXTENSION);
        if(in_array(strtolower($ext), ['jpg','jpeg','png','gif'])){ ?>
            <img src="../<?php echo $row['answer_text']; ?>" class="q-image">
        <?php } else { ?>
            <a href="../<?php echo $row['answer_text']; ?>" target="_blank">Download File</a>
        <?php } ?>
    <?php } else { ?>
        <p><?php echo nl2br(htmlspecialchars($row['answer_text'])); ?></p>
    <?php } ?>

    <!-- Marks Input -->
    <label>Marks: <input type="number" step="0.1" name="marks[<?php echo $row['question_id']; ?>]" value="0"></label>
</div>
<?php } ?>

<button type="submit">Save Marks</button>
</form>

</body>
</html>
