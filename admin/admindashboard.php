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
              <a class="nav-link" href="add_assaignment.php">       <i class="fa-solid fa-file-circle-question"></i>assaignment</a>
</li>
<li class="nav-item">
              <a class="nav-link" href="chat_dashboard.php">       <i class="fa-solid fa-file-circle-question"></i>chats</a>
</li>
<li class="nav-item">
              <a class="nav-link" href="add_exam.php">       <i class="fa-solid fa-file-circle-question"></i>Add Exam</a>
</li>

<li class="nav-item">
              <a class="nav-link" href="pass_evaluateexamid.php">       <i class="fa-solid fa-file-circle-question"></i>Evaluate Exam</a>
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
       $sql="SELECT * FROM assignment_submissions";
       $result=$conn->query($sql);
       $totalass=$result->num_rows;
              $sql="SELECT * FROM quiz_results";
       $result=$conn->query($sql);
       $totalquiz=$result->num_rows;
      

      
      
     
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
              <div class="card-header"> Studnet Assaignment</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalass ?></h4>
                <a class="btn btn-light" href="pass_assaignmentid.php">View</a>
              </div>
            </div>
          
          </div>

                                    <div class="col-md-4 mt-4">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
              <div class="card-header"> Quiz information</div>
              <div class="card-body">
                <h4 class="card-title"><?php echo $totalquiz ?></h4>
                <a class="btn btn-light" href="quiz_information.php">View</a>
              </div>
            </div>


          
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
