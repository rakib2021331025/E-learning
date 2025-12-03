<?php
include('../dbconnection.php');
include('./admininclude/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Sales Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
>
        <!-- Main Content -->
        <div class="col-md-9" style="margin-left:300px;">
            <h3 class="text-center mb-4">Course Sales Report</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Transaction ID</th>
                            <th>Course ID</th>
                            <th>Amount (৳)</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT stu_name, stu_email, tran_id, course_id, amount, order_date, stat 
                                FROM course_order 
                                ORDER BY order_date DESC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['stu_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['stu_email']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['tran_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['course_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['stat']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No sales records found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


</body>
</html>
