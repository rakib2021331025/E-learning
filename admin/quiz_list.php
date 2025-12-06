<?php 
include('../dbconnection.php');
session_start();

$course_id = $_GET['course_id'] ?? null;

// Filter quizzes
if ($course_id) {
    $q = $conn->query("SELECT * FROM quizzes WHERE course_id = '$course_id'");
} else {
    $q = $conn->query("SELECT * FROM quizzes");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .quiz-card { transition: transform 0.2s; }
        .quiz-card:hover { transform: scale(1.03); }
        .quiz-actions a { margin-right: 10px; }
        h2 { margin-top: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container mt-5">

    <h2>All Quizzes</h2>

    <!-- Add Quiz Button -->
    <a href="add_quiz.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add New Quiz</a>

    <div class="row">
    <?php 
    if ($q->num_rows > 0) {
        while($row = $q->fetch_assoc()){ 
    ?>
        <div class="col-md-4 mb-4">
            <div class="card quiz-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['quiz_title']; ?></h5>
                    <p class="card-text"><small class="text-muted">Course ID: <?php echo $row['course_id']; ?></small></p>
                    <div class="quiz-actions">
                        <a href="add_question.php?quiz_id=<?php echo $row['quiz_id']; ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Add Question
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php 
        }
    } else {
        echo '<p class="text-danger">No quizzes found!</p>';
    }
    ?>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
