<?php
include '../dbconnection.php';
session_start();

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}

// Create assignment_submissions table if not exists
$table_check = $conn->query("SHOW TABLES LIKE 'assignment_submissions'");
if($table_check->num_rows == 0){
    $create_table = "CREATE TABLE assignment_submissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        assignment_id INT NOT NULL,
        student_email VARCHAR(255) NOT NULL,
        file_path VARCHAR(500) NOT NULL,
        marks DECIMAL(10,2) DEFAULT 0,
        feedback TEXT,
        status VARCHAR(50) DEFAULT 'Pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        evaluated_at TIMESTAMP NULL,
        INDEX idx_assignment (assignment_id),
        INDEX idx_student (student_email),
        INDEX idx_status (status)
    )";
    $conn->query($create_table);
}

if(isset($_POST['submit_assaignment'])){
    $assignment_id = intval($_POST['assignment_id']);
    $stuEmail = $student;

    // File Upload
    if(!isset($_FILES['file']) || $_FILES['file']['error'] != 0){
        die("File upload error. Please select a file.");
    }
    
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed_exts = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
    
    if(!in_array($ext, $allowed_exts)){
        die("Invalid file type. Allowed: PDF, DOC, DOCX, TXT, JPG, PNG, ZIP, RAR");
    }

    $folder = "../uploads/assignments/";
    if(!is_dir($folder)) mkdir($folder, 0777, true);

    $filename = "ass_" . str_replace(['@', '.'], '_', $stuEmail) . "_" . $assignment_id . "_" . time() . "." . $ext;
    $file_path = $folder . $filename;
    
    if(!move_uploaded_file($tmp, $file_path)){
        die("File upload failed. Please try again.");
    }
    
    $relative_path = "uploads/assignments/" . $filename;

    // Check if already submitted
    $check_sql = "SELECT id FROM assignment_submissions 
                  WHERE assignment_id = $assignment_id AND student_email = '$stuEmail'";
    $check_result = $conn->query($check_sql);
    
    if($check_result && $check_result->num_rows > 0){
        // Update existing submission
        $update_sql = "UPDATE assignment_submissions 
                       SET file_path = '$relative_path', 
                           submitted_at = NOW(),
                           status = 'Pending'
                       WHERE assignment_id = $assignment_id AND student_email = '$stuEmail'";
        $result = $conn->query($update_sql);
    } else {
        // Insert new submission
        $sql = "INSERT INTO assignment_submissions (assignment_id, student_email, file_path, status)
                VALUES($assignment_id, '$stuEmail', '$relative_path', 'Pending')";
        $result = $conn->query($sql);
    }

    if($conn->query($sql)){

        // assignment_id থেকে course_id বের করা
        $q = $conn->query("SELECT course_id FROM assignments WHERE id = '$assignment_id'");
        $row = $q->fetch_assoc();
        $course_id = $row['course_id'];

        // সুন্দর CSS Styled Success Message
        echo "
        <html>
        <head>
            <style>
                body{
                    background:#f4f7fc;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    height:100vh;
                    font-family:Arial;
                }
                .success-box{
                    background:white;
                    width:420px;
                    padding:30px;
                    border-radius:15px;
                    box-shadow:0 0 15px rgba(0,0,0,0.15);
                    text-align:center;
                    animation:pop 0.4s ease;
                }
                @keyframes pop{
                    0%{transform:scale(0.8);}
                    100%{transform:scale(1);}
                }
                .success-box i{
                    font-size:60px;
                    color:#28a745;
                    margin-bottom:15px;
                }
                .success-box h2{
                    color:#333;
                    margin-bottom:10px;
                }
                .success-box p{
                    color:#555;
                    margin-bottom:25px;
                }
                .btn{
                    padding:12px 25px;
                    background:#007bff;
                    color:white;
                    text-decoration:none;
                    border-radius:8px;
                    font-size:16px;
                    transition:0.25s;
                }
                .btn:hover{
                    background:#0056b3;
                }
            </style>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'>
        </head>

        <body>

        <div class='success-box'>
            <i class='fas fa-check-circle'></i>
            <h2>Assignment Submitted!</h2>
            <p>Your assignment was uploaded successfully.</p>
            <a class='btn' href='pass_course_id_assaignment.php?course_id=$course_id'>Go Back</a>
        </div>

        </body>
        </html>
        ";
    }
}
?>
