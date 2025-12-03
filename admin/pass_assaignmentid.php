<?php
session_start();
include '../dbconnection.php';

// All assignments load
$q = $conn->query("SELECT * FROM assignments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Assignments</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 20px;
        }

        h2{
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table{
            width: 70%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        table th{
            background: #007bff;
            color: white;
            padding: 12px;
            font-size: 16px;
            text-transform: uppercase;
        }

        table td{
            padding: 12px;
            font-size: 15px;
            color: #333;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        tr:hover{
            background: #f1f5ff;
        }

        a.view-btn{
            padding: 7px 12px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
            font-size: 14px;
        }

        a.view-btn:hover{
            background: #1e7e34;
        }
    </style>

</head>
<body>

<h2>All Assignments</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Course</th>
        <th>Title</th>
        <th>Deadline</th>
        <th>Action</th>
    </tr>

    <?php while($row = $q->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['deadline']; ?></td>
            <td>
                <a class="view-btn" href="view_submission.php?assignment_id=<?php echo $row['id']; ?>">
                    View Submissions
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
