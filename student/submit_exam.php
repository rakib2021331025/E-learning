<?php
session_start();
date_default_timezone_set("Asia/Dhaka");
include "../dbconnection.php";

// Check for multiple session variable names (support different login methods)
$student = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($student) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}

if(!isset($_POST['exam_id'])){
    die("Invalid exam request.");
}

$exam_id = intval($_POST['exam_id']);

// Fetch questions
$q_res = $conn->query("SELECT * FROM exam_questions WHERE exam_id=$exam_id");
if(!$q_res || $q_res->num_rows==0){
    die("No questions found!");
}

// Directory for file uploads
$upload_dir = "../uploads/answers/";
if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0777, true);
}

// Check if exam_answers table exists, if not create it
$table_check = $conn->query("SHOW TABLES LIKE 'exam_answers'");
if($table_check->num_rows == 0){
    $create_table = "CREATE TABLE exam_answers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exam_id INT NOT NULL,
        student_email VARCHAR(255) NOT NULL,
        question_id INT NOT NULL,
        given_option VARCHAR(1),
        answer_text TEXT,
        file_path VARCHAR(500),
        obtained_mark DECIMAL(10,2) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_answer (exam_id, student_email, question_id),
        INDEX idx_exam_student (exam_id, student_email),
        INDEX idx_question (question_id)
    )";
    $conn->query($create_table);
}

while($q = $q_res->fetch_assoc()){

    $qid = $q['id'];
    $field_name = "q".$qid;
    $given_option = "";
    $answer_text = "";
    $file_path = "";
    $mark = 0; // Default mark

    // File upload answer (written type)
    if($q['question_type'] == "written"){
        if(isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] == 0){
            $ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            if(in_array($ext, $allowed_exts)){
                $filename = "ans_".str_replace(['@', '.'], '_', $student)."_".$qid."_".time().".".$ext;
                $filepath = $upload_dir . $filename;

                if(move_uploaded_file($_FILES[$field_name]['tmp_name'], $filepath)){
                    $file_path = "uploads/answers/".$filename;
                }
            }
        }
    } else if($q['question_type'] == "mcq") {
        // MCQ answer
        $given_option = isset($_POST[$field_name]) ? strtoupper(trim($_POST[$field_name])) : "";
        
        // Auto-marking for MCQ questions
        if(!empty($given_option) && !empty($q['correct_option'])){
            if($given_option == strtoupper(trim($q['correct_option']))){
                $mark = isset($q['marks']) ? floatval($q['marks']) : 1; // Use question marks or default 1
            } else {
                $mark = 0; // Wrong answer gets 0
            }
        }
    } else {
        // Text / short / long answer
        $answer_text = isset($_POST[$field_name]) ? trim($_POST[$field_name]) : "";
    }

    // Check if answer already exists (prevent duplicate submission)
    $check_stmt = $conn->prepare("
        SELECT id FROM exam_answers 
        WHERE exam_id = ? AND question_id = ? AND student_email = ?
    ");
    $check_stmt->bind_param("iis", $exam_id, $qid, $student);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0){
        // Update existing answer
        $stmt = $conn->prepare("
            UPDATE exam_answers 
            SET given_option = ?, answer_text = ?, file_path = ?, obtained_mark = ?
            WHERE exam_id = ? AND question_id = ? AND student_email = ?
        ");
        if(!$stmt){
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sssdiss", $given_option, $answer_text, $file_path, $mark, $exam_id, $qid, $student);
        $stmt->execute();
    } else {
        // Insert new answer
        $stmt = $conn->prepare("
            INSERT INTO exam_answers (exam_id, question_id, student_email, given_option, answer_text, file_path, obtained_mark)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        if(!$stmt){
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iissssd", $exam_id, $qid, $student, $given_option, $answer_text, $file_path, $mark);
        $stmt->execute();
    }
}

// Update exam_results table
$total_marks_query = $conn->query("
    SELECT COALESCE(SUM(obtained_mark), 0) as total 
    FROM exam_answers 
    WHERE exam_id = $exam_id AND student_email = '$student'
");
$total_marks = $total_marks_query ? $total_marks_query->fetch_assoc()['total'] : 0;

$check_result = $conn->query("
    SELECT id FROM exam_results 
    WHERE exam_id = $exam_id AND student_email = '$student'
");

if($check_result && $check_result->num_rows > 0){
    $conn->query("
        UPDATE exam_results 
        SET obtained_marks = $total_marks, 
            status = 'pending'
        WHERE exam_id = $exam_id AND student_email = '$student'
    ");
} else {
    $conn->query("
        INSERT INTO exam_results (exam_id, student_email, obtained_marks, total_marks, status)
        VALUES ($exam_id, '$student', $total_marks, $total_marks, 'pending')
    ");
}

echo "<h2>Exam Submitted Successfully!</h2>";
echo "<a href='pass_examid.php'>Back to Exam List</a>";
?>
