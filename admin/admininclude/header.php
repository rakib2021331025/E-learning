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
  <link rel="stylesheet" href="../css/responsive.css" />
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
      <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar d-print-none" style="position:fixed; top:40px; height:100vh; overflow-y:auto;width;200px;">
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
              <a class="nav-link" href="contact_messages.php"><i class="fas fa-envelope"></i> Contacts</a>
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
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
    <script src="../js/firebase-auth.js"></script>

</body>
</html>
