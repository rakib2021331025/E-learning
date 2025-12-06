<?php
session_start();
include '../dbconnection.php';

// Check student login
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';
if(empty($student) || empty($_SESSION['is_login'])){
    header("Location: ../loginorsignup.php");
    exit();
}

// Check quiz_id
if(!isset($_GET['quiz_id']) || empty($_GET['quiz_id'])){
    die("Quiz ID missing!");
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz questions
$sql = "SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Start Quiz</title>
<style>
body { background:#f0f0f0; font-family:Arial; padding:20px; }
.box { max-width:800px; margin:auto; background:#fff; padding:25px; border-radius:10px; }
.qbox { margin-bottom:25px; padding:20px; background:#fafafa; border-radius:8px; }
.qbox h3 { margin-bottom:12px; color:#333; }
.option { margin:5px 0; display:block; padding:8px; background:#fff; border-radius:6px; }
.btn {
    display:inline-block; padding:10px 18px;
    background:#007bff; color:#fff;
    text-decoration:none; border-radius:6px;
}
</style>
</head>
<body>

<div class="box">
    <h2>Quiz Questions</h2>

    <form method="POST" action="submit_quiz.php">
        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

        <?php
        $i = 1;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
        ?>
        <div class="qbox">
            <h3><?= $i++ ?>. <?= htmlspecialchars($row['question_text']); ?></h3>

            <?php 
                $options = [
                    "A" => $row["option_a"],
                    "B" => $row["option_b"],
                    "C" => $row["option_c"],
                    "D" => $row["option_d"]
                ];

                // CORRECT FIX: radio button group unique for each question
                foreach($options as $key => $val){
            ?>
                <label class="option">
                    <input type="radio" name="answers[<?= $row['id'] ?>]" value="<?= $key ?>" required>
                    <?= htmlspecialchars($val) ?>
                </label>
            <?php } ?>
        </div>
        <?php } } else { echo "<p>No questions found!</p>"; } ?>

        <button type="submit" class="btn">Submit Quiz</button>
    </form>
</div>

</body>
</html>
