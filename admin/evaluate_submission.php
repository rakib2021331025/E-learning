<?php
include "../dbconnection.php";
session_start();

if(!isset($_GET['submission_id'])){
    die("Submission ID missing!");
}

$submission_id = intval($_GET['submission_id']);

// Fetch submission
$sub = $conn->query("SELECT * FROM exam_submissions WHERE id=$submission_id")->fetch_assoc();

// Fetch answers
$answers = $conn->query("
    SELECT ea.*, eq.question_text, eq.question_type, eq.correct_option, eq.image_path
    FROM exam_answers ea
    JOIN exam_questions eq ON ea.question_id = eq.id
    WHERE ea.submission_id=$submission_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evaluate Submission</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        p {
            font-weight: bold;
            color: #555;
        }
        input[type="number"] {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100px;
            margin: 5px 0 15px 0;
        }
        img {
            max-width: 300px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 3px;
        }
        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            display: block;
            margin: 20px auto 0 auto;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mcq-note {
            color: #555;
            font-style: italic;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>Evaluate Submission: <?php echo htmlspecialchars($sub['student_email']); ?></h2>

<form method="POST" action="save_evaluation.php">
    <input type="hidden" name="submission_id" value="<?php echo $submission_id; ?>">

    <?php while($ans = $answers->fetch_assoc()){ ?>
        <p><?php echo htmlspecialchars($ans['question_text']); ?></p>
        <?php if($ans['image_path']){ ?>
            <img src="../<?php echo $ans['image_path']; ?>" alt="Question Image"><br>
        <?php } ?>

        <strong>Student Answer:</strong> <?php echo htmlspecialchars($ans['answer']); ?><br>

        <?php if($ans['question_type'] != "mcq"){ ?>
            <label>Mark:</label>
            <input type="number" name="mark_<?php echo $ans['id']; ?>" value="<?php echo $ans['mark']; ?>" min="0"><br>
        <?php } else { ?>
            <p class="mcq-note">(MCQ auto-marked: <?php echo $ans['mark']; ?>)</p>
        <?php } ?>

        <hr>
    <?php } ?>

    <button type="submit">Save Evaluation</button>
</form>

</body>
</html>
