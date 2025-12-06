<?php
if (!isset($_SESSION)) {
  session_start();
}
$loginstatus = isset($_SESSION['is_login']) && $_SESSION['is_login'] === true;
?>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark pl-5 fixed-top">
  <a class="navbar-brand" href="index.php">ShekharJagat</a>
  <span class="navbar-text">Learn and Implement</span>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <ul class="navbar-nav custom-nav pl-5">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="allcourse.php">Courses</a>
      </li>
             <li class="nav-item">
        <a class="nav-link" href="stupaymentstatus.php">Payment Status</a>
      </li>


      <?php if ($loginstatus) { ?>
        <li class="nav-item">
          <a class="nav-link" href="student/studentprofile.php">My Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#LoginModal">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#RegModal">Signup</a>
        </li>
      <?php } ?>

      <li class="nav-item">
        <a class="nav-link" href="stu_feedback.php">Feedback</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact</a>
      </li>
    </ul>
  </div>
</nav>
<!-- Srudent Registration Modal -->
<div class="modal fade" id="RegModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Student Registration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="regForm">
          <div class="mb-3">
            <label><b><i class="fa-solid fa-user" style="margin-right:5px;"></i>Name:</b></label>
                        <small id="stunamemsg"></small>

            <input type="text" placeholder="Name" class="form-control" required id="stuname">

            <label><i class="fa-solid fa-envelope" style="margin-right:5px;"></i>Email:</label>
                                    <small id="stuemailmsg"></small>

            <input type="email" placeholder="Email" class="form-control" required id="stuemail">

          </div>
          <div class="mb-3">
            <label><i class="fa-solid fa-key" style="margin-right:5px;"></i>New Password:</label>
                                    <small id="stupassmsg"></small>

            <input type="password" placeholder="Password" class="form-control" required id="stupass">
                    

          </div>

          <!-- Google Sign-In Button -->
          <div class="mb-3 text-center">
            <button type="button" class="btn btn-danger w-100" id="firebase-google-signup" style="background-color: #db4437;">
              <i class="fab fa-google"></i> Continue with Google
            </button>
          </div>
          
          <div class="text-center mb-3">
            <span style="color: #999;">OR</span>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <span id="showmsg"></span>
            <button type="button" class="btn btn-primary" id="signup">Sign up</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:black;">Close</button>
          </div>

          <!-- Message box -->
          <div id="showmsg" class="mt-3 text-center"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!---login---->
<!-- Login Modal -->
<div class="modal fade" id="LoginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Student Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form>

          <div class="mb-3">
            <label><i class="fa-solid fa-envelope me-2"></i>Email:</label>
            <input type="email" class="form-control" placeholder="Email" required id="stulogemail">
          </div>

          <div class="mb-3">
            <label><i class="fa-solid fa-key me-2"></i>Password:</label>
            <input type="password" class="form-control" placeholder="Password" required id="stulogpass">
          </div>

          <!-- Google Sign-In Button -->
          <div class="mb-3 text-center">
            <button type="button" class="btn btn-danger w-100" id="firebase-google-login" style="background-color: #db4437;">
              <i class="fab fa-google"></i> Continue with Google
            </button>
          </div>
          
          <div class="text-center mb-3">
            <span style="color: #999;">OR</span>
          </div>

          <!-- Button Row Right Aligned -->
          <div class="d-flex justify-content-end gap-2">
            <div id="logshowmsg" class="mt-3 text-center"></div>
            <button type="button" class="btn btn-primary" id="stulog">Login</button>
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>         
          </div>
          
        </form>
      </div>

    </div>
  </div>
</div>
