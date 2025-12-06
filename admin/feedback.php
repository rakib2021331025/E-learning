<?php 
if (!isset($_SESSION)) session_start();
include('./admininclude/header.php');
include('../dbconnection.php');
?>

<div class="col-sm-9 mt-5"style="margin-left:300px;"> 
    <p class="bg-dark text-white p-2">List of Feedback</p>

    <?php
    // Delete handler before output
    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $del_id = intval($_POST['id']);
        $sql = "DELETE FROM feedback WHERE f_id = $del_id";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success">Feedback Deleted Successfully</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to Delete Feedback</div>';
        }
    }

    // Fetch and display feedback list
    $sql = "SELECT * FROM feedback";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">Feedback ID</th> 
                <th scope="col">Content</th>
                <th scope="col">Student ID</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row">' . $row['f_id'] . '</th>';
            echo '<td>' . $row['f_content'] . '</td>';
            echo '<td>' . $row['stu_id'] . '</td>';
            echo '<td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id" value="' . $row['f_id'] . '">
                        <button type="submit" class="btn btn-danger btn-sm" name="delete" onclick="return confirm(\'Are you sure?\');">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                </td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo "<div class='alert alert-info'>No Feedback Found</div>";
    }
    ?>
</div>
