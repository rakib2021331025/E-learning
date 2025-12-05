<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

include_once('../dbConnection.php');

$stu_img = "";
$stulogEmail = $_SESSION['stulogEmail'] ?? $_SESSION['stulogEmail'] ?? '';

if (!empty($stulogEmail)) {
  if (isset($_SESSION['stu_img'])) {
    $stu_img = $_SESSION['stu_img'];
  } else {
    $sql = "SELECT stu_img FROM student WHERE stu_email='$stulogEmail'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stu_img = $row['stu_img'];
      $_SESSION['stu_img'] = $stu_img;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

  <!-- Bootstrap & Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/adminstyle.css" />
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
  
  /* Sidebar style */
  
</head>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Profile</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/all.min.css">
  <link rel="stylesheet" href="../css/adminstyle.css">
  <link rel="stylesheet" href="../css/responsive.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
  <style>
    body {
      margin: 0;
    }
    .sidebar {
      position: fixed;
      top: 60px; /* navbar height */
      left: 0;
      width: 250px;
      height: calc(100vh - 60px);
      background-color: #f8f9fa;
      border-right: 1px solid #ddd;
      overflow-y: auto;
    }
    .content-area {
      margin-left: 250px; /* sidebar width */
      height: calc(100vh - 60px);
      display: flex;
      align-items: center;     /* vertical center */
      justify-content: center; /* horizontal center */
      padding: 20px;
      background-color: #e9ecef;
    }
    
    /* Responsive for mobile */
    @media screen and (max-width: 768px) {
      .sidebar {
        width: 100% !important;
        position: relative !important;
        height: auto !important;
        top: 0 !important;
      }
      .content-area {
        margin-left: 0 !important;
        height: auto !important;
        padding: 10px !important;
      }
    }
  </style>
</body>  </style>
</head>
<body>
  
<!-- Sidebar -->
<nav class="navbar navbar-dark fixed-top flex-md-nowrap p-0 shadow" style="background-color:#225470;">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Elearning</a>
</nav>

<div class="container-fluid" style="margin-top:70px;">
  <div class="row">
    <nav class="col-sm-2 bg-light sidebar py-5 d-print-none">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item mb-3 text-center">
            <img src="<?php echo htmlspecialchars($stu_img); ?>" alt="student image" class="img-thumbnail rounded-circle" style="height:100px;width:100px;">
          </li>
                 <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-home"></i>Home</a></li>

          <li class="nav-item"><a class="nav-link" href="studentprofile.php"><i class="fas fa-user"></i> Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="mycourse.php"><i class="fab fa-accessible-icon"></i> My Course</a></li>
          <li class="nav-item"><a class="nav-link" href="stufeedback.php"><i class="fas fa-comment"></i> Feedback</a></li>
          <li class="nav-item"><a class="nav-link" href="stuchangepass.php"><i class="fas fa-key"></i> Change Password</a></li>
          <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
           <li class="nav-item"><a class="nav-link" href="studentliveclass.php"><i class="fa-solid fa-video"></i>
 Live class</a></li>
        <li class="nav-item">
    <a class="nav-link" href="studentdashboard.php">
        <i class="fa-solid fa-circle-question"></i> Quiz
    </a>
</li>
        <li class="nav-item">
    <a class="nav-link" href="pass_course_id_assaignment.php">
        <i class="fa-solid fa-file-lines"></i>

  Veiw Assaignment
    </a>
</li>
        <li class="nav-item">
    <a class="nav-link" href="chat.php">
<i class="fa-solid fa-comment-dots"></i>
  chat with admin
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="pass_examid.php">
        <i class="fa-solid fa-clipboard-check"></i>

  Live exam
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="student_performance.php">
        <i class="fa-solid fa-clipboard-check"></i>

  My Performance
    </a>
</li>




        </ul>
      </div>
    </nav>
  </div>
</div>
</body>
</html>