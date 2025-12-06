<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/all.min.css">

<style>
body { font-family: Arial, sans-serif; }
#sidebar {
    background-color: #343a40;
    color: white;
    min-height: 100vh;
    transition: all 0.3s;
    position: fixed;
    top: 0; left: 0;
    width: 250px;
    z-index: 999;
}
#sidebar .nav-link { color: white; }
#sidebar .nav-link:hover { background-color: #495057; }
#sidebarCollapse { display: none; }
#sidebar-overlay {
    position: fixed;
    display: none;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 998;
}
@media (max-width: 768px) {
    #sidebar { left: -250px; }
    #sidebar.active { left: 0; }
    #sidebarCollapse { display: inline-block; margin-left: 10px; }
    #sidebar-overlay.active { display: block; }
}
#content { margin-left: 250px; padding: 20px; transition: all 0.3s; }
@media (max-width: 768px) {
    #content { margin-left: 0; }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark fixed-top p-2 shadow" style="background-color:#225470;">
    <button class="btn btn-dark d-md-none" id="sidebarCollapse"><i class="fas fa-bars"></i></button>
    <a class="navbar-brand ms-2" href="admindashboard.php">Elearning <small class="text-white">Admin Area</small></a>
</nav>

<div id="sidebar">
    <ul class="nav flex-column pt-5">
        <li class="nav-item"><a class="nav-link" href="admindashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="cources.php"><i class="fas fa-book"></i> Courses</a></li>
        <li class="nav-item"><a class="nav-link" href="lesson.php"><i class="fab fa-accessible-icon"></i> Lessons</a></li>
        <li class="nav-item"><a class="nav-link" href="student.php"><i class="fas fa-users"></i> Students</a></li>
        <li class="nav-item"><a class="nav-link" href="paymentstatus.php"><i class="fas fa-credit-card"></i> Payment Status</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="fas fa-comment"></i> Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="adminchangepass.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <li class="nav-item"><a class="nav-link" href="Teacherliveclass.php"><i class="fa-solid fa-video"></i> Live class</a></li>
        <li class="nav-item"><a class="nav-link" href="quiz_list.php"><i class="fa-solid fa-file-circle-question"></i> Quiz</a></li>
        <li class="nav-item"><a class="nav-link" href="add_assaignment.php"><i class="fa-solid fa-file-lines"></i> Assignment</a></li>
        <li class="nav-item"><a class="nav-link" href="chat_dashboard.php"><i class="fa-regular fa-comment"></i> Chats</a></li>
        <li class="nav-item"><a class="nav-link" href="add_exam.php"><i class="fa-solid fa-plus"></i> Add Exam</a></li>
        <li class="nav-item"><a class="nav-link" href="pass_evaluateexamid.php"><i class="fa-solid fa-clipboard-check"></i> Evaluate Exam</a></li>
    </ul>
</div>
<div id="sidebar-overlay"></div>

<div id="content" class="pt-5">
    <div class="container-fluid">

    <div class="row text-center">
        <?php
        include('../dbconnection.php');
        $totalstu = $conn->query("SELECT * FROM student")->num_rows;
        $totalcourse = $conn->query("SELECT * FROM course")->num_rows;
        $totalsol = $conn->query("SELECT * FROM course_order")->num_rows;
        $totalass = $conn->query("SELECT COUNT(*) as total FROM assignment_submissions")->fetch_assoc()['total'];
        $pending_ass = $conn->query("SELECT COUNT(*) as total FROM assignment_submissions WHERE status='Pending'")->fetch_assoc()['total'];
        $totalquiz = $conn->query("SELECT COUNT(*) as total FROM quizzes")->fetch_assoc()['total'];
        $total_quiz_submissions = $conn->query("SELECT COUNT(DISTINCT student_email) as total FROM quiz_results")->fetch_assoc()['total'];
        $total_quiz_attempts = $conn->query("SELECT COUNT(*) as total FROM quiz_results")->fetch_assoc()['total'];
        $totalcontact = $conn->query("SELECT COUNT(*) as total FROM contact")->fetch_assoc()['total'];
        $total_exams = $conn->query("SELECT COUNT(DISTINCT exam_id) as total FROM exam_answers")->fetch_assoc()['total'];
        ?>

        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Courses</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalcourse;?></h4>
                    <a class="btn btn-light" href="cources.php">View</a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Students</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalstu;?></h4>
                    <a class="btn btn-light" href="student.php">View</a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Sold</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalsol;?></h4>
                    <a class="btn btn-light" href="paymentstatus.php">View</a>
                </div>
            </div>
        </div>

        <!-- Assignment Submissions -->
        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Assignment Submissions</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalass;?></h4>
                    <?php if($pending_ass>0){ ?>
                        <small style="color:#ffc107;"><?php echo $pending_ass;?> Pending</small><br>
                    <?php } ?>
                    <a class="btn btn-light" href="pass_assaignmentid.php">View All</a>
                </div>
            </div>
        </div>

        <!-- Assignment Marking -->
        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Assignment Marking Info</div>
                <div class="card-body">
                    <a class="btn btn-light" href="assaignment_marking_information.php">View</a>
                </div>
            </div>
        </div>

        <!-- Total Quizzes -->
        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Quizzes</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalquiz;?></h4>
                    <small style="color:#d4edda;"><?php echo $total_quiz_submissions;?> Students Submitted</small><br>
                    <small style="color:#d4edda;"><?php echo $total_quiz_attempts;?> Total Attempts</small><br>
                    <a class="btn btn-light" href="quiz_information.php">View Results</a>
                </div>
            </div>
        </div>

        <!-- Contact Messages -->
        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Contact Messages</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalcontact;?></h4>
                    <a class="btn btn-light" href="contact_messages.php">View</a>
                </div>
            </div>
        </div>

        <!-- Evaluate Exam -->
        <div class="col-sm-6 col-md-4 mt-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Evaluate Exam</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $total_exams;?></h4>
                    <a class="btn btn-light" href="pass_evaluateexamid.php">Evaluate</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $('#sidebarCollapse').on('click', function(){
        $('#sidebar').toggleClass('active');
        $('#sidebar-overlay').toggleClass('active');
    });
    $('#sidebar-overlay').on('click', function(){
        $('#sidebar').removeClass('active');
        $(this).removeClass('active');
    });
});
</script>
</body>
</html>
