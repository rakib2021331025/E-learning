<?php
include '../dbconnection.php';
session_start();

$stuEmail = $_SESSION['stulogEmail'];
$course_id = $_GET['course_id'];

$q = $conn->query("SELECT * FROM assignments WHERE course_id='$course_id'");

echo "
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fc;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
.assignment-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 20px;
    margin: 20px auto;          /* auto margin দিয়ে center-align */
    width: 600px; 
    hieght:450px;              /* width ছোট করা */
    transition: transform 0.2s, box-shadow 0.2s;
}
.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.assignment-card h3 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .assignment-card p {
            color: #555;
            margin-bottom: 10px;
        }
        .deadline {
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .assignment-card form {
            display: flex;
            flex-direction: column;
        }
        .assignment-card input[type='file'] {
            padding: 5px;
            margin-bottom: 10px;
        }
        .assignment-card button {
            background: #28a745;
            color: #fff;
            padding: 6px;
            border: none;
            border-radius: 8px;
            width: 180px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.25s;
        }
        .assignment-card button:hover {
            background: #218838;
        }
    </style>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'>
</head>
<body>
    <h2>Assignments</h2>
";

while($a = $q->fetch_assoc()){
    echo "<div class='assignment-card'>";
    echo "<h3><i class='fas fa-file-alt'></i> {$a['title']}</h3>";
    echo "<p>{$a['description']}</p>";
    echo "<div class='deadline'>Deadline: {$a['deadline']}</div>";

    echo "<form method='POST' enctype='multipart/form-data' action='submit_assaignment.php'>
            <input type='hidden' name='assignment_id' value='{$a['id']}'>
            <input type='file' name='file' required>
            <button type='submit' name='submit_assaignment'><i class='fas fa-upload'></i> Submit Assignment</button>
          </form>";
    echo "</div>";
}

echo "</body></html>";
?>
