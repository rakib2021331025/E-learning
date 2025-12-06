<?php
include "../dbconnection.php";
session_start();
include './admininclude/header.php';

// Fetch all exams
$res = $conn->query("SELECT * FROM exams ORDER BY start_time DESC");
?>

<style>
.evaluation-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 40px);
    padding: 30px;
}

.evaluation-header {
    color: white;
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: bold;
}

.exam-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 25px;
    margin-bottom: 25px;
    transition: transform 0.3s;
}

.exam-card:hover {
    transform: translateY(-5px);
}

.exam-title {
    color: #667eea;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

.exam-info {
    color: #666;
    margin-bottom: 10px;
}

.submission-count {
    background: #28a745;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: bold;
    display: inline-block;
    margin-bottom: 15px;
}

.student-list {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
}

.student-item {
    background: white;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.student-email {
    color: #333;
    font-weight: 500;
}

.evaluate-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.evaluate-btn:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.status-evaluated {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.no-exams {
    background: white;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    color: #666;
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="evaluation-container">
        <div class="container">
            <h2 class="evaluation-header">
                <i class="fa-solid fa-clipboard-check"></i> Exam Evaluation
            </h2>
            
            <?php if($res && $res->num_rows > 0): ?>
                <div class="row">
                    <?php while($exam = $res->fetch_assoc()): 
                        $exam_id = $exam['id'];
                        
                        // Count unique students who submitted answers for this exam
                        $submissions_query = $conn->query("
                            SELECT COUNT(DISTINCT student_email) as count 
                            FROM exam_answers 
                            WHERE exam_id = $exam_id
                        ");
                        $submissions_count = $submissions_query ? $submissions_query->fetch_assoc()['count'] : 0;
                        
                        // Get list of students who submitted
                        $students_submitted = $conn->query("
                            SELECT DISTINCT student_email 
                            FROM exam_answers 
                            WHERE exam_id = $exam_id
                        ");
                    ?>
                        <div class="col-md-12 mb-4">
                            <div class="exam-card">
                                <h4 class="exam-title">
                                    <i class="fas fa-file-alt"></i> <?php echo htmlspecialchars($exam['title']); ?>
                                </h4>
                                
                                <div class="exam-info">
                                    <i class="fas fa-book"></i> Course ID: <strong><?php echo $exam['course_id']; ?></strong>
                                </div>
                                <div class="exam-info">
                                    <i class="fas fa-clock"></i> Start: <?php echo date('d M Y, h:i A', strtotime($exam['start_time'])); ?>
                                </div>
                                <div class="exam-info">
                                    <i class="fas fa-hourglass-end"></i> End: <?php echo date('d M Y, h:i A', strtotime($exam['end_time'])); ?>
                                </div>
                                
                                <?php if($submissions_count > 0): ?>
                                    <div class="submission-count">
                                        <i class="fas fa-users"></i> <?php echo $submissions_count; ?> Student(s) Submitted
                                    </div>
                                    
                                    <div class="student-list">
                                        <h5 style="color: #667eea; margin-bottom: 15px;">
                                            <i class="fas fa-list"></i> Student Submissions:
                                        </h5>
                                        <?php while($student = $students_submitted->fetch_assoc()): 
                                            $student_email = $student['student_email'];
                                            
                                            // Check if already evaluated
                                            $evaluated = $conn->query("
                                                SELECT status FROM exam_results 
                                                WHERE exam_id = $exam_id 
                                                AND student_email = '$student_email'
                                            ");
                                            $is_evaluated = $evaluated && $evaluated->num_rows > 0;
                                        ?>
                                            <div class="student-item">
                                                <div>
                                                    <span class="student-email">
                                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($student_email); ?>
                                                    </span>
                                                    <?php if($is_evaluated): ?>
                                                        <span class="status-badge status-evaluated">✓ Evaluated</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-pending">⏳ Pending</span>
                                                    <?php endif; ?>
                                                </div>
                                                <a href="evaluate_exam.php?exam_id=<?php echo $exam_id; ?>&student=<?php echo urlencode($student_email); ?>" 
                                                   class="evaluate-btn">
                                                    <i class="fas fa-check-circle"></i> Evaluate
                                                </a>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No submissions yet for this exam.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-exams">
                    <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h4>No Exams Available</h4>
                    <p>There are no exams to evaluate at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
