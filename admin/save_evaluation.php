<?php
include "../dbconnection.php";
session_start();

if(isset($_POST['submission_id'])){
    $submission_id = intval($_POST['submission_id']);
    $total = 0;

    foreach($_POST as $key => $value){
        if(strpos($key, "mark_") === 0){
            $ans_id = intval(str_replace("mark_", "", $key));
            $mark = intval($value);
            $conn->query("UPDATE exam_answers SET mark=$mark WHERE id=$ans_id");
            $total += $mark;
        }
    }

    // Add MCQ marks
    $mcq_total = $conn->query("SELECT SUM(mark) as mcq_sum FROM exam_answers 
                               WHERE submission_id=$submission_id AND mark>0")->fetch_assoc()['mcq_sum'];
    $total += $mcq_total;

    // Update submission
    $conn->query("UPDATE exam_submissions SET total_mark=$total, status='evaluated' WHERE id=$submission_id");

    echo "Evaluation saved! Total Mark: $total";
   echo "<br><a href='admindashboard.php'>Back to Home</a>";
}
