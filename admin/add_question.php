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

    echo "<p class='success'>Question Added Successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Question</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f4f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-container {
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 400px;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: #555;
    }
    input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        transition: 0.3s;
    }
    input[type="text"]:focus {
        border-color: #007BFF;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }
    button {
        width: 100%;
        padding: 12px;
        background: #007BFF;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background: #0056b3;
    }
    .success {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>Add Question</h2>
    <form method="POST">
        <label>Question:</label>
        <input type="text" name="question" required>

        <label>Option A:</label>
        <input type="text" name="a" required>

        <label>Option B:</label>
        <input type="text" name="b" required>

        <label>Option C:</label>
        <input type="text" name="c" required>

        <label>Option D:</label>
        <input type="text" name="d" required>

        <label>Correct Option (A/B/C/D):</label>
        <input type="text" name="correct" maxlength="1" pattern="[ABCDabcd]" required>

        <button name="add">Add Question</button>
    </form>
</div>

</body>
</html>
