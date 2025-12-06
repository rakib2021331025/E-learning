<?php
include '../dbconnection.php';
session_start();

// Quiz Result + Student JOIN
$sql = "
SELECT qr.result_id, qr.quiz_id, qr.student_email, qr.obtained_marks, s.stu_name
FROM quiz_results AS qr
INNER JOIN student AS s
ON qr.student_email = s.stu_email
WHERE qr.result_id IN (
    SELECT MAX(result_id)
    FROM quiz_results
    GROUP BY quiz_id, student_email
)
ORDER BY qr.quiz_id DESC;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            padding:20px;
        }

        h2{
            text-align:center;
            color:#333;
            margin-bottom:25px;
        }

        .result-table{
            width:90%;
            margin:auto;
            border-collapse: collapse;
            box-shadow:0px 3px 10px rgba(0,0,0,0.1);
            background:white;
            border-radius:8px;
            overflow:hidden;
        }

        .result-table th{
            background:#4a76fd;
            color:white;
            padding:12px;
            font-size:16px;
            text-align:left;
        }

        .result-table td{
            padding:12px;
            font-size:15px;
            border-bottom:1px solid #eee;
        }

        .result-table tr:hover{
            background:#f1f5ff;
            transition:0.3s;
        }

        .result-table tr:last-child td{
            border-bottom:none;
        }
    </style>
</head>
<body>

<h2>All Quiz Results</h2>

<table class="result-table">
    <tr>
        <th>Student Name</th>
        <th>Student Email</th>
        <th>Quiz ID</th>
        <th>Marks</th>
    </tr>

    <?php while($row = $result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['stu_name']; ?></td>
            <td><?php echo $row['student_email']; ?></td>
            <td><?php echo $row['quiz_id']; ?></td>
            <td><?php echo $row['obtained_marks']; ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
