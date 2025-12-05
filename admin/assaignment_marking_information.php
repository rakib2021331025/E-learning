<?php
session_start();
include '../dbconnection.php';

// Optional admin check
// if(!isset($_SESSION['adminemail'])){ header("Location: ../index.php"); exit(); }

$sql = "SELECT id, assignment_id, student_email, marks, feedback, status 
        FROM assignment_submissions ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Assignment Marking Information</title>

<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
body {
    background: #e9f0f7;
    font-family: Poppins, Arial;
}

/* Container */
.table-box {
    max-width: 1100px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Heading */
.table-box h3 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
    color: #333;
}

/* Table styling */
table {
    border-radius: 10px;
    overflow: hidden;
}

thead {
    background: #343a40 !important;
    color: #fff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f7f9fc;
}

.table td, .table th {
    vertical-align: middle !important;
    font-size: 15px;
}

/* Badges */
.badge {
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 6px;
}

/* Status badge */
.badge-success {
    background: #28a745;
}
.badge-warning {
    background: #f0ad4e;
}
.badge-secondary {
    background: #6c757d;
}

/* Feedback box */
.feedback-box {
    max-width: 250px;
    margin: auto;
    word-wrap: break-word;
}

/* Hover row effect */
tbody tr:hover {
    background: #e8f4ff !important;
    cursor: pointer;
    transition: 0.2s;
}
</style>

</head>
<body>

<div class="table-box">
    <h3>Assignment Marking Information</h3>

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Assignment ID</th>
                <th>Student Email</th>
                <th>Marks</th>
                <th>Feedback</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td><?= $row['id']; ?></td>

                <td><b><?= $row['assignment_id']; ?></b></td>

                <td><?= htmlspecialchars($row['student_email']); ?></td>

                <!-- Marks -->
                <td>
                    <?php 
                        if($row['marks'] !== NULL && $row['marks'] !== ""){
                            echo "<span class='badge badge-success'>{$row['marks']} Marks</span>";
                        } else {
                            echo "<span class='badge badge-secondary'>Not Marked</span>";
                        }
                    ?>
                </td>

                <!-- Feedback -->
                <td>
                    <div class="feedback-box">
                        <?php 
                            if(!empty($row['feedback'])){
                                echo htmlspecialchars($row['feedback']);
                            } else {
                                echo "<span class='text-muted'>No Feedback</span>";
                            }
                        ?>
                    </div>
                </td>

                <!-- Status -->
                <td>
                    <?php
                        if($row['status'] == "Checked"){
                            echo "<span class='badge badge-success'>Checked</span>";
                        } else {
                            echo "<span class='badge badge-warning'>Pending</span>";
                        }
                    ?>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6'>No Assignment Submissions Found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
