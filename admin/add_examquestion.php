<?php
include "../dbconnection.php";
session_start();

// Check exam_id
if(!isset($_GET['exam_id'])){
    die("Exam ID missing!");
}

$exam_id = intval($_GET['exam_id']);

if(isset($_POST['addQ'])){
    $type = $_POST['question_type'];
    $text = $conn->real_escape_string($_POST['question_text']);

    $a = $_POST['option_a'] ?? '';
    $b = $_POST['option_b'] ?? '';
    $c = $_POST['option_c'] ?? '';
    $d = $_POST['option_d'] ?? '';
    $correct = $_POST['correct_option'] ?? '';
    $marks = isset($_POST['marks']) ? floatval($_POST['marks']) : 1;

    // Handle image upload
    $image_path = NULL;
    if(isset($_FILES['question_image']) && $_FILES['question_image']['error'] == 0){
        $ext = pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION);
        $filename = "uploads/questions/".uniqid().".".$ext;
        if(!is_dir('uploads/questions')){
            mkdir('uploads/questions', 0777, true);
        }
        move_uploaded_file($_FILES['question_image']['tmp_name'], $filename);
        $image_path = $filename;
    }

    $sql = "INSERT INTO exam_questions(exam_id, question_type, question_text,
            option_a, option_b, option_c, option_d, correct_option, image_path, marks)
            VALUES($exam_id, '$type', '$text', '$a','$b','$c','$d','$correct', 
            ".($image_path ? "'$image_path'" : "NULL").", $marks)";

    if($conn->query($sql)){
        echo "<p class='success'>Question Added!</p>";
    } else {
        echo "<p class='error'>Error: ".$conn->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Questions</title>
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
        }
        form {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            max-width: 700px;
            margin: 20px auto;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        select, input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 15px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        #mcqFields {
            padding: 15px;
            background-color: #fafafa;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
            text-align: center;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Add Questions for Exam ID: <?php echo $exam_id; ?></h2>

<form method="POST" enctype="multipart/form-data">
    <label>Question Type:</label>
    <select name="question_type" onchange="showMCQ(this.value)">
        <option value="mcq">MCQ</option>
        <option value="short">Short Answer</option>
        <option value="written">Written</option>
    </select>

    <label>Question Text:</label>
    <textarea name="question_text" required></textarea>

    <label>Question Image (optional):</label>
    <input type="file" name="question_image" accept="image/*">

    <div id="mcqFields">
        <label>A:</label> <input type="text" name="option_a"><br>
        <label>B:</label> <input type="text" name="option_b"><br>
        <label>C:</label> <input type="text" name="option_c"><br>
        <label>D:</label> <input type="text" name="option_d"><br>
        <label>Correct Option:</label> <input type="text" name="correct_option"><br>
    </div>

   <label>Marks:</label>
   <input type="number" name="marks" step="0.1" min="0" value="1" required>

    <div style="text-align:center; margin-top:15px;">
        <button name="addQ">Add Question</button>
    </div>
</form>

<script>
function showMCQ(val){
    document.getElementById('mcqFields').style.display = (val=="mcq") ? "block" : "none";
}
window.onload = function(){
    showMCQ(document.querySelector('select[name="question_type"]').value);
}
</script>

</body>
</html>
