<?php 
include('../dbconnection.php');
session_start();

$quiz_id = $_GET['quiz_id'];

if(isset($_POST['add'])){
    $q = $_POST['question'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $correct = $_POST['correct'];

    $conn->query("INSERT INTO questions(quiz_id,question_text,option_a,option_b,option_c,option_d,correct_option)
                  VALUES('$quiz_id','$q','$a','$b','$c','$d','$correct')");

    echo "<p>Question Added Successfully!</p>";
}
?>

<h2>Add Question</h2>
<form method="POST">
    Question: <input type="text" name="question"><br><br>
    A: <input type="text" name="a"><br><br>
    B: <input type="text" name="b"><br><br>
    C: <input type="text" name="c"><br><br>
    D: <input type="text" name="d"><br><br>
    Correct Option (A/B/C/D): <input type="text" name="correct"><br><br>
    <button name="add">Add Question</button>
</form>
