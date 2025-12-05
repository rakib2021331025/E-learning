<?php
session_start();
include "../dbconnection.php";
include "./admininclude/header.php";

// Admin login check
$adminEmail = $_SESSION['adminEmail'] ?? $_SESSION['adminemail'] ?? '';
if(empty($adminEmail) || !isset($_SESSION['loginstatus'])){
    header("Location: addadmin.php");
    exit();
}

// Check exam_id and student_email
if(!isset($_GET['exam_id']) || !isset($_GET['student'])){
    die("Invalid request!");
}

$exam_id = intval($_GET['exam_id']);
$student_email = $_GET['student'];

// Fetch exam title
$exam_res = $conn->query("SELECT * FROM exams WHERE id=$exam_id");
if(!$exam_res || $exam_res->num_rows==0){
    die("Exam not found!");
}
$exam = $exam_res->fetch_assoc();

// Fetch student's answers
$ans_res = $conn->query("
    SELECT ea.*, eq.question_text, eq.question_type, eq.option_a, eq.option_b, eq.option_c, eq.option_d, eq.image_path, eq.correct_option, eq.marks as question_marks
    FROM exam_answers ea
    JOIN exam_questions eq ON ea.question_id = eq.id
    WHERE eq.exam_id = $exam_id AND ea.exam_id = $exam_id AND ea.student_email='$student_email'
    ORDER BY eq.id ASC
");

if(!$ans_res || $ans_res->num_rows==0){
    die("No answers found for this student!");
}

?>

<style>
.evaluation-wrapper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 40px);
    padding: 30px;
}

.evaluation-header-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.exam-title-header {
    color: #667eea;
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
}

.student-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.question-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.question-card:hover {
    transform: translateY(-3px);
}

.question-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    margin: -25px -25px 20px -25px;
    font-size: 18px;
    font-weight: bold;
}

.question-text {
    font-size: 16px;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.6;
}

.answer-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border-left: 4px solid #28a745;
}

.answer-label {
    color: #28a745;
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

.q-image {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    margin: 10px 0;
}

.marks-input-section {
    background: #fff3cd;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
}

.marks-input {
    width: 120px;
    padding: 10px;
    border: 2px solid #ffc107;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
}

.marks-input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.submit-section {
    text-align: center;
    margin-top: 30px;
    padding: 20px;
}

.save-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
}

.back-btn {
    background: #6c757d;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    margin-left: 15px;
    transition: all 0.3s;
}

.back-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="evaluation-wrapper">
        <div class="container">
            <div class="evaluation-header-card">
                <h2 class="exam-title-header">
                    <i class="fas fa-clipboard-check"></i> Evaluate Exam
                </h2>
                <h3 style="color: #666; font-size: 20px; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($exam['title']); ?>
                </h3>
                <div class="student-info">
                    <i class="fas fa-user"></i> <strong>Student:</strong> <?php echo htmlspecialchars($student_email); ?>
                </div>
            </div>

            <form method="POST" action="save_marks.php">
                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">

                <?php $q_num = 1; while($row = $ans_res->fetch_assoc()): ?>
                    <div class="question-card">
                        <div class="question-header">
                            Question <?php echo $q_num++; ?>
                        </div>
                        
                        <p class="question-text">
                            <?php echo htmlspecialchars($row['question_text']); ?>
                        </p>

                        <!-- Question Image -->
                        <?php if(!empty($row['image_path'])): ?>
                            <img src="../admin/<?php echo htmlspecialchars($row['image_path']); ?>" 
                                 class="q-image" 
                                 alt="Question Image">
                        <?php endif; ?>

                        <!-- Student Answer -->
                        <div class="answer-section">
                            <span class="answer-label">
                                <i class="fas fa-pencil-alt"></i> Student Answer:
                            </span>
                            
                            <?php 
                            // Get answer based on question type
                            if($row['question_type'] == 'mcq'){
                                $answer_field = isset($row['given_option']) ? $row['given_option'] : '';
                            } else if($row['question_type'] == 'written'){
                                $answer_field = isset($row['file_path']) ? $row['file_path'] : '';
                            } else {
                                $answer_field = isset($row['answer_text']) ? $row['answer_text'] : '';
                            }
                            
                            if($row['question_type']=='written' || $row['question_type']=='file'): 
                                if(!empty($answer_field)):
                                    // Check if answer is a file path
                                    $ext = strtolower(pathinfo($answer_field, PATHINFO_EXTENSION));
                                    $image_exts = ['jpg','jpeg','png','gif'];
                                    $file_exts = ['pdf','doc','docx','txt'];
                                    
                                    if(in_array($ext, $image_exts)): ?>
                                        <div style="margin: 10px 0;">
                                            <img src="../<?php echo htmlspecialchars($answer_field); ?>" 
                                                 class="q-image" 
                                                 alt="Student Answer"
                                                 style="max-width: 100%; max-height: 500px; border: 2px solid #28a745; border-radius: 8px; padding: 5px;">
                                            <br>
                                            <a href="../<?php echo htmlspecialchars($answer_field); ?>" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success mt-2">
                                                <i class="fas fa-external-link-alt"></i> Open Full Size
                                            </a>
                                        </div>
                                    <?php elseif(in_array($ext, $file_exts)): ?>
                                        <div style="margin: 10px 0;">
                                            <a href="../<?php echo htmlspecialchars($answer_field); ?>" 
                                               target="_blank" 
                                               class="btn btn-primary">
                                                <i class="fas fa-download"></i> Download File (<?php echo strtoupper($ext); ?>)
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div style="margin: 10px 0;">
                                            <a href="../<?php echo htmlspecialchars($answer_field); ?>" 
                                               target="_blank" 
                                               class="btn btn-primary">
                                                <i class="fas fa-download"></i> Download Answer File
                                            </a>
                                        </div>
                                    <?php endif;
                                else:
                                    echo "<p class='text-muted'><i class='fas fa-exclamation-circle'></i> No answer submitted</p>";
                                endif;
                            elseif($row['question_type']=='mcq'): ?>
                                <p><strong>Selected:</strong> <?php echo htmlspecialchars($answer_field ?: 'Not answered'); ?></p>
                                <?php if(isset($row['correct_option'])): ?>
                                    <p><strong>Correct Answer:</strong> <?php echo htmlspecialchars($row['correct_option']); ?></p>
                                <?php endif;
                            else: ?>
                                <p style="white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($answer_field ?: 'No answer provided')); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Marks Input -->
                        <?php 
                        $current_mark = isset($row['obtained_mark']) ? $row['obtained_mark'] : 0;
                        $max_marks = isset($row['question_marks']) ? $row['question_marks'] : 1;
                        
                        if($row['question_type'] != 'mcq'): ?>
                            <div class="marks-input-section">
                                <label style="font-weight: bold; color: #856404;">
                                    <i class="fas fa-star"></i> Marks (Max: <?php echo $max_marks; ?>):
                                </label>
                                <input type="number" 
                                       step="0.1" 
                                       name="marks[<?php echo $row['question_id']; ?>]" 
                                       value="<?php echo $current_mark; ?>" 
                                       min="0"
                                       max="<?php echo $max_marks; ?>"
                                       class="marks-input"
                                       required>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> MCQ auto-marked: <?php echo $current_mark; ?> / <?php echo $max_marks; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>

                <div class="submit-section">
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Save Marks
                    </button>
                    <a href="pass_evaluateexamid.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Exam List
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
