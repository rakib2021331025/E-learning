<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/adminstyle.css" />
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-dark fixed-top p-0 shadow" style="background-color:#225470;">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="admindashboard.php">
      Elearning <small class="text-white">Admin Area</small>
    </a>
  </nav>

  <!-- Main Content -->
  <div class="container-fluid" style="margin-top:40px;">
    <div class="row">
      
      <!-- Sidebar -->
      <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar d-print-none" style="position:fixed; top:40px; height:100vh; overflow-y:auto;">
        <div class="sidebar-sticky py-5">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="admindashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cources.php"><i class="fas fa-book"></i> Courses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="lesson.php"><i class="fab fa-accessible-icon"></i> Lessons</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="student.php"><i class="fas fa-users"></i> Students</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="paymentstatus.php"><i class="fas fa-credit-card"></i> Payment Status</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="feedback.php"><i class="fas fa-comment"></i> Feedback</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="adminchangepass.php"><i class="fas fa-key"></i> Change Password</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Teacherliveclass.php"><i class="fa-solid fa-video"></i>Live class</a>
</li>
            <li class="nav-item">
              <a class="nav-link" href="quiz_list.php">       <i class="fa-solid fa-file-circle-question"></i>quiz</a>
</li>
            <li class="nav-item">
              <a class="nav-link" href="add_assaignment.php">     <i class="fa-solid fa-file-lines"></i>
assaignment</a>
</li>
<li class="nav-item">
              <a class="nav-link" href="chat_dashboard.php">   <i class="fa-regular fa-comment"></i>
chats</a>
</li>
<li class="nav-item">
              <a class="nav-link" href="add_exam.php">   <i class="fa-solid fa-plus"></i>
Add Exam</a>
</li>

<li class="nav-item">
              <a class="nav-link" href="pass_evaluateexamid.php">  <i class="fa-solid fa-clipboard-check"></i>
Evaluate Exam</a>
</li>









          


          </ul>
        </div>
      </nav>
      <?php 
       include('../dbconnection.php');
       $sql="SELECT * FROM student";
       $result=$conn->query($sql);
       $totalstu=$result->num_rows;
        $sql="SELECT * FROM course";
       $result=$conn->query($sql);
       $totalcourse=$result->num_rows;
              $sql="SELECT * FROM course_order";
       $result=$conn->query($sql);
       $totalsol=$result->num_rows;
       // Assignment submissions count
       $sql="SELECT COUNT(*) as total FROM assignment_submissions";
       $result=$conn->query($sql);
       $totalass = $result ? $result->fetch_assoc()['total'] : 0;
       
       // Pending assignment submissions count
       $sql_pending_ass = "SELECT COUNT(*) as total FROM assignment_submissions WHERE status = 'Pending'";
       $result_pending_ass = $conn->query($sql_pending_ass);
       $pending_ass = $result_pending_ass ? $result_pending_ass->fetch_assoc()['total'] : 0;
       
       // Total quizzes count
       $sql_quizzes = "SELECT COUNT(*) as total FROM quizzes";
       $result_quizzes = $conn->query($sql_quizzes);
       $totalquiz = $result_quizzes ? $result_quizzes->fetch_assoc()['total'] : 0;
       
       // Quiz submissions count (unique students who took quizzes)
       $sql_quiz_submissions = "SELECT COUNT(DISTINCT student_email) as total FROM quiz_results";
       $result_quiz_submissions = $conn->query($sql_quiz_submissions);
       $total_quiz_submissions = $result_quiz_submissions ? $result_quiz_submissions->fetch_assoc()['total'] : 0;
       
       // Total quiz attempts
       $sql_quiz_attempts = "SELECT COUNT(*) as total FROM quiz_results";
       $result_quiz_attempts = $conn->query($sql_quiz_attempts);
       $total_quiz_attempts = $result_quiz_attempts ? $result_quiz_attempts->fetch_assoc()['total'] : 0;
       
       // Contact messages count
       $sql="SELECT COUNT(*) as total FROM contact";
       $result=$conn->query($sql);
       $totalcontact = $result ? $result->fetch_assoc()['total'] : 0;
      
      
      
      
     
      ?>

      <!-- Dashboard Cards -->
      <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
        <div class="row text-center">
          <div class="col-md-4 mt-4">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
              <div class="card-header">Courses</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalcourse ?></h4>
                <a class="btn btn-light" href="cources.php">View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 mt-4">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
              <div class="card-header">Students</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalstu ?></h4>
                <a class="btn btn-light" href="student.php">View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 mt-4">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
              <div class="card-header">Sold</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalsol ?></h4>
                <a class="btn btn-light" href="paymentstatus.php">View</a>
              </div>
            </div>
            </div>

          </div>
        </div>
      </main>
    </div>
    
    </div>
  </div>
  <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
        <div class="row text-center">
          <div class="col-md-4 mt-4">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
              <div class="card-header">
                <i class="fas fa-file-alt"></i> Assignment Submissions
              </div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalass ?></h4>
                <?php if($pending_ass > 0): ?>
                  <small style="color: #ffc107;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $pending_ass; ?> Pending
                  </small><br>
                <?php endif; ?>
                <a class="btn btn-light" href="pass_assaignmentid.php">
                  <i class="fas fa-eye"></i> View All
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-4 mt-4">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
              <div class="card-header">
                <i class="fas fa-question-circle"></i> Total Quizzes
              </div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalquiz ?></h4>
                <small style="color: #d4edda;">
                  <i class="fas fa-users"></i> <?php echo $total_quiz_submissions; ?> Students Submitted
                </small><br>
                <small style="color: #d4edda;">
                  <i class="fas fa-chart-line"></i> <?php echo $total_quiz_attempts; ?> Total Attempts
                </small><br>
                <a class="btn btn-light" href="quiz_information.php">
                  <i class="fas fa-eye"></i> View Results
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mt-4">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
              <div class="card-header"> Contact Messages</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalcontact ?></h4>
                <a class="btn btn-light" href="contact_messages.php">View</a>
              </div>
            </div>
          </div>
        </div>

        
      </main>
      
     <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4"> 
      <div class="col-md-4 mt-4">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
              <div class="card-header"> assaignment marking information</div>
              <div class="card-body">
                <h4 class="card-title"></h4>
                <a class="btn btn-light" href="assaignment_marking_information.php">View</a>
              </div>
            </div>
          </div>
        </div>  
<div class="row text-center">
          <div class="col-md-4 mt-4">
            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
              <div class="card-header">
                <i class="fa-solid fa-file-circle-question"></i> Evaluate Exam
              </div>
              <div class="card-body">
                <?php 
                $sql_exam = "SELECT COUNT(DISTINCT exam_id) as total FROM exam_answers";
                $result_exam = $conn->query($sql_exam);
                $total_exams = $result_exam ? $result_exam->fetch_assoc()['total'] : 0;
                ?>
                <h4 class="card-title"><?php echo $total_exams; ?></h4>
                <a class="btn btn-light" href="pass_evaluateexamid.php">
                  <i class="fa-solid fa-clipboard-check"></i> Evaluate
                </a>
              </div>
            </div>
          </div>
        </div>
        
      </main>

    </div>
    
    </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.min.js"></script>
    <script type="text/javascript" src="js/ajaxrequest.js"></script>
        <script type="text/javascript" src="js/adminajax.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

</body>
</html>
