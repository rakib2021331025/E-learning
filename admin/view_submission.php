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

        <a class="download-btn" href="<?php echo $s['file_path']; ?>" target="_blank">
            Download Submission
        </a>

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

    $sql = "UPDATE assignment_submissions 
            SET marks='$marks', feedback='$feedback', status='Checked'
            WHERE id='$id'";

    if($conn->query($sql)){
        echo "<script>
                alert('Marks Updated Successfully!');
                window.location='admindashboard.php';
              </script>";
    }
}
?>

</body>
</html>
