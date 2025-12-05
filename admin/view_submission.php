<?php
include '../dbconnection.php';
session_start();

$assignment_id = $_GET['assignment_id'];

$q = $conn->query("SELECT * FROM assignment_submissions WHERE assignment_id='$assignment_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignment Submissions</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f7fc;
            margin:0;
            padding:20px;
        }

        h2{
            text-align:center;
            color:#333;
            margin-bottom:25px;
        }

        .card{
            width: 70%;
            background:white;
            margin:20px auto;
            padding:20px;
            border-radius:12px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            animation:fade 0.4s ease;
        }

        @keyframes fade{
            0%{opacity:0; transform:translateY(10px);}
            100%{opacity:1; transform:translateY(0);}
        }

        .student-info{
            font-size:17px;
            margin-bottom:10px;
            color:#444;
        }

        a.download-btn{
            display:inline-block;
            padding:10px 18px;
            background:#007bff;
            color:white;
            border-radius:6px;
            text-decoration:none;
            margin-bottom:15px;
            transition:0.3s;
        }
        a.download-btn:hover{
            background:#0056b3;
        }

        input[type="number"], textarea{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:15px;
            margin-top:5px;
        }

        textarea{
            height:80px;
            resize:none;
        }

        .submit-btn{
            margin-top:15px;
            padding:10px 20px;
            background:#28a745;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
            transition:0.3s;
        }
        .submit-btn:hover{
            background:#1e7e34;
        }
    </style>
</head>

<body>

<h2>Assignment Submissions</h2>

<?php while($s = $q->fetch_assoc()){ ?>
    <div class="card">

        <p class="student-info"><b>Student:</b> <?php echo $s['student_email']; ?></p>

        <a class="download-btn" href="../<?php echo htmlspecialchars($s['file_path']); ?>" target="_blank">
            <i class="fas fa-download"></i> Download Submission
        </a>
        
        <p style="color: #666; margin: 10px 0;">
            <i class="fas fa-clock"></i> Submitted: <?php echo date('d M Y, h:i A', strtotime($s['submitted_at'] ?? 'now')); ?>
        </p>
        
        <?php if($s['status'] == 'Checked'): ?>
            <p style="color: #28a745; font-weight: bold;">
                <i class="fas fa-check-circle"></i> Status: Evaluated
            </p>
        <?php else: ?>
            <p style="color: #ffc107; font-weight: bold;">
                <i class="fas fa-hourglass-half"></i> Status: Pending Evaluation
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="submit_id" value="<?php echo $s['id']; ?>">

            <label><b>Marks:</b></label>
            <input type="number" name="marks" value="<?php echo $s['marks']; ?>">

            <br><br>

            <label><b>Feedback:</b></label>
            <textarea name="feedback"><?php echo $s['feedback']; ?></textarea>

            <button class="submit-btn" name="giveMarks">Update Marks</button>
        </form>

    </div>
<?php } ?>

<?php
// Update marks section
if(isset($_POST['giveMarks'])){
    $id = $_POST['submit_id'];
    $marks = $_POST['marks'];
    $feedback = $_POST['feedback'];

    $marks = floatval($marks);
    $feedback = $conn->real_escape_string($feedback);
    $id = intval($id);
    
    $sql = "UPDATE assignment_submissions 
            SET marks = $marks, 
                feedback = '$feedback', 
                status = 'Checked',
                evaluated_at = NOW()
            WHERE id = $id";

    if($conn->query($sql)){
        if($conn->affected_rows > 0){
            echo "<script>
                    alert('Marks and Feedback Updated Successfully!');
                    window.location.reload();
                  </script>";
        } else {
            echo "<script>
                    alert('No changes made or record not found.');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error: " . addslashes($conn->error) . "');
              </script>";
    }
}
?>

</body>
</html>
