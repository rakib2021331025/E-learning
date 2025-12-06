<?php
include '../dbconnection.php';
session_start();

if(isset($_POST['addAssignment'])){
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO assignments(course_id, title, description, deadline)
            VALUES('$course_id', '$title', '$desc', '$deadline')";
    if($conn->query($sql)){
        echo "<script>alert('Assignment Added!');</script>";
    }
}
?>

<html>
<head>
<style>
    body{
        font-family: Arial;
        background: #f4f7fc;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin:0;
    }

    .form-box{
        background: #fff;
        width: 420px;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        animation: pop 0.3s ease;
    }
    @keyframes pop{
        0%{transform: scale(.9);}
        100%{transform: scale(1);}
    }

    .form-box h2{
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    select, input[type='text'], textarea, input[type='date']{
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
        transition: 0.2s;
    }
    select:focus, input:focus, textarea:focus{
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.4);
    }

    textarea{
        height: 90px;
        resize: none;
    }

    button{
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #007bff;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.25s;
    }
    button:hover{
        background: #0056b3;
    }
</style>
</head>

<body>

<div class="form-box">
    <h2>Add Assignment</h2>

    <form method="POST">
        <select name="course_id" required>
            <?php
            $c = $conn->query("SELECT * FROM course");
            while($row = $c->fetch_assoc()){
                echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
            }
            ?>
        </select>

        <input type="text" name="title" placeholder="Assignment Title" required>

        <textarea name="desc" placeholder="Description..." required></textarea>

        <input type="date" name="deadline" required>

        <button name="addAssignment">Add Assignment</button>
    </form>
</div>

</body>
</html>
