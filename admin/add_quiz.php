<?php 
include('../dbconnection.php');
session_start();
include './admininclude/header.php';

// Fetch all courses for dropdown
$courses_res = $conn->query("SELECT * FROM course ORDER BY course_name ASC");

if(isset($_POST['add_quiz'])){
    $course = intval($_POST['course_id']);
    $title = $conn->real_escape_string($_POST['quiz_title']);
    $marks = intval($_POST['total_marks']);
    $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';

    if(empty($course) || empty($title)){
        $error_msg = "Please fill all required fields!";
    } else {
        $sql = "INSERT INTO quizzes(course_id, quiz_title, total_marks, description, status)
                VALUES($course, '$title', $marks, '$description', 1)";

        if($conn->query($sql)){
            $id = $conn->insert_id;
            echo "<script>
                    alert('Quiz Created Successfully!');
                    window.location.href='add_question.php?quiz_id=$id';
                  </script>";
            exit();
        } else {
            $error_msg = "Error: " . $conn->error;
        }
    }
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f7f8;
}

.quiz-form-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 40px);
    padding: 30px;
}

.quiz-form-card {
    background: white;
    border-radius: 15px;
    padding: 30px 40px;
    max-width: 700px;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.quiz-form-header {
    color: #667eea;
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px solid #667eea;
    padding-bottom: 15px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
    font-size: 15px;
}

.form-group label .required {
    color: #dc3545;
}

.form-group select,
.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.form-group select:focus,
.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    margin-top: 10px;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.error-msg {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #dc3545;
}

.success-msg {
    background: #d4edda;
    color: #155724;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #28a745;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #667eea;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

.back-link:hover {
    color: #764ba2;
    text-decoration: none;
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="quiz-form-container">
        <div class="quiz-form-card">
            <h2 class="quiz-form-header">
                <i class="fas fa-plus-circle"></i> Add New Quiz
            </h2>

            <?php if(isset($error_msg)): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>
                        <i class="fas fa-book"></i> Select Course <span class="required">*</span>
                    </label>
                    <select name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php while($course = $courses_res->fetch_assoc()): ?>
                            <option value="<?php echo $course['course_id']; ?>">
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-heading"></i> Quiz Title <span class="required">*</span>
                    </label>
                    <input type="text" name="quiz_title" placeholder="Enter quiz title" required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i> Description (Optional)
                    </label>
                    <textarea name="description" placeholder="Enter quiz description"></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-star"></i> Total Marks <span class="required">*</span>
                    </label>
                    <input type="number" name="total_marks" placeholder="Enter total marks" min="1" value="10" required>
                </div>

                <button type="submit" name="add_quiz" class="submit-btn">
                    <i class="fas fa-save"></i> Create Quiz
                </button>

                <a href="quiz_list.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Quiz List
                </a>
            </form>
        </div>
    </div>
</main>

