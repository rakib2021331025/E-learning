<?php
session_start();
include "../dbconnection.php";

if(!isset($_SESSION['adminEmail'])){
    header("Location: addadmin.php");
    exit();
}

if(!isset($_POST['exam_id']) || !isset($_POST['student_email'])){
    die("Invalid request!");
}

$exam_id = intval($_POST['exam_id']);
$student_email = $_POST['student_email'];
$total_marks = 0;

// Process marks for each question
$total_marks = 0;
$processed_question_ids = [];

if(isset($_POST['marks']) && is_array($_POST['marks'])){
    foreach($_POST['marks'] as $question_id => $mark_value){
        $question_id = intval($question_id);
        $mark_value = floatval($mark_value);
        $processed_question_ids[] = $question_id;
        
        // Update mark for this answer
        $update_sql = "UPDATE exam_answers 
                       SET obtained_mark = $mark_value 
                       WHERE exam_id = $exam_id
                       AND question_id = $question_id 
                       AND student_email = '$student_email'";
        $result = $conn->query($update_sql);
        
        if(!$result){
            echo "Error updating marks: " . $conn->error;
        }
        
        $total_marks += $mark_value;
    }
}

// Also calculate MCQ marks if any (already auto-marked during submission)
// Only count MCQ marks that were NOT manually evaluated (to avoid double counting)
if(!empty($processed_question_ids)){
    // Use prepared statement for safety
    $placeholders = str_repeat('?,', count($processed_question_ids) - 1) . '?';
    $mcq_stmt = $conn->prepare("
        SELECT COALESCE(SUM(ea.obtained_mark), 0) as mcq_sum 
        FROM exam_answers ea
        JOIN exam_questions eq ON ea.question_id = eq.id
        WHERE eq.exam_id = ? 
        AND ea.exam_id = ?
        AND ea.student_email = ?
        AND eq.question_type = 'mcq'
        AND ea.question_id NOT IN ($placeholders)
    ");
    $types = 'iis' . str_repeat('i', count($processed_question_ids));
    $params = array_merge([$exam_id, $exam_id, $student_email], $processed_question_ids);
    $mcq_stmt->bind_param($types, ...$params);
    $mcq_stmt->execute();
    $mcq_result = $mcq_stmt->get_result();
    $mcq_query = $mcq_result;
} else {
    // If no manual marks were given, count all MCQ marks
    $mcq_stmt = $conn->prepare("
        SELECT COALESCE(SUM(ea.obtained_mark), 0) as mcq_sum 
        FROM exam_answers ea
        JOIN exam_questions eq ON ea.question_id = eq.id
        WHERE eq.exam_id = ? 
        AND ea.exam_id = ?
        AND ea.student_email = ?
        AND eq.question_type = 'mcq'
    ");
    $mcq_stmt->bind_param("iis", $exam_id, $exam_id, $student_email);
    $mcq_stmt->execute();
    $mcq_result = $mcq_stmt->get_result();
    $mcq_query = $mcq_result;
}

if($mcq_query && $mcq_query->num_rows > 0){
    $mcq_total = $mcq_query->fetch_assoc()['mcq_sum'];
    $total_marks += floatval($mcq_total);
}

// Calculate total possible marks
$total_possible = $conn->query("
    SELECT COALESCE(SUM(marks), 0) as total 
    FROM exam_questions 
    WHERE exam_id = $exam_id
")->fetch_assoc()['total'];

$percentage = $total_possible > 0 ? round(($total_marks / $total_possible) * 100, 2) : 0;

// Update/create exam_results table
$check_result = $conn->query("
    SELECT id FROM exam_results 
    WHERE exam_id = $exam_id 
    AND student_email = '$student_email'
");

if($check_result && $check_result->num_rows > 0){
    // Update existing result
    $conn->query("
        UPDATE exam_results 
        SET obtained_marks = $total_marks, 
            total_marks = $total_possible,
            percentage = $percentage,
            status = 'evaluated',
            evaluated_at = NOW()
        WHERE exam_id = $exam_id 
        AND student_email = '$student_email'
    ");
} else {
    // Create new result entry
    $conn->query("
        INSERT INTO exam_results (exam_id, student_email, obtained_marks, total_marks, percentage, status, evaluated_at)
        VALUES ($exam_id, '$student_email', $total_marks, $total_possible, $percentage, 'evaluated', NOW())
    ");
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Saved</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\" />
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css\" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-box {
            background: white;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounce 0.6s;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
        h2 { 
            color: #28a745;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: left;
        }
        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
        .btn-container {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn-custom {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        .btn-secondary-custom {
            background: #6c757d;
            color: white;
        }
        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class='success-box'>
        <div class='success-icon'>
            <i class='fas fa-check-circle'></i>
        </div>
        <h2>Evaluation Saved Successfully!</h2>
        
        <div class='info-item'>
            <div class='info-label'><i class='fas fa-user'></i> Student:</div>
            <div class='info-value'>" . htmlspecialchars($student_email) . "</div>
        </div>
        
        <div class='info-item'>
            <div class='info-label'><i class='fas fa-star'></i> Total Marks:</div>
            <div class='info-value' style='color: #28a745; font-size: 24px;'>" . number_format($total_marks, 2) . "</div>
        </div>
        
        <div class='btn-container'>
            <a href='pass_evaluateexamid.php' class='btn-custom btn-primary-custom'>
                <i class='fas fa-list'></i> Back to Exam List
            </a>
            <a href='admindashboard.php' class='btn-custom btn-secondary-custom'>
                <i class='fas fa-tachometer-alt'></i> Dashboard
            </a>
        </div>
    </div>
</body>
</html>";
?>

