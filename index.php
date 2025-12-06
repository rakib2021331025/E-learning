<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
  <meta name="mobile-web-app-capable" content="yes">
  <title>ShekharJagat</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>

  <style>
    body { background-color: #655289; color: white; font-family: Arial, sans-serif; }

    /* Testimonial */
    .testimonial { background-color: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 15px; margin: 10px; }
    .testimonial .description { font-style: italic; font-size: 15px; }
    .testimonial .testimonial-proof h5 { font-weight: bold; }
    .testimonial .pic img { width: 80px; height: 80px; object-fit: cover; }

    /* Video */
    .vid-parent { position: relative; width: 100%; max-height: 400px; overflow: hidden; border-radius: 10px; }
    .vid-parent video { width: 100%; height: 100%; object-fit: cover; }
    .vid-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; padding: 0 10px; }

    /* Buttons responsive */
    .vid-content a.btn { font-size: 14px; padding: 8px 12px; }

    /* Text Banner */
    .txt-banner h5 { font-size: 14px; }

    /* Popular courses button */
    .text-center a.btn { font-size: 14px; padding: 6px 12px; }

    @media (max-width: 768px){
      .vid-parent { max-height: 250px; }
      .vid-content h1 { font-size: 20px; }
      .vid-content small { font-size: 14px; }
      .testimonial .description { font-size: 13px; }
      .testimonial .pic img { width: 60px; height: 60px; }
      .txt-banner h5 { font-size: 12px; }
    }

    @media (max-width: 480px){
      .vid-parent { max-height: 200px; }
      .vid-content h1 { font-size: 18px; }
      .vid-content small { font-size: 12px; }
      .vid-content a.btn { font-size: 12px; padding: 6px 10px; }
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <?php include('./dbconnection.php'); include('./navbar.php'); ?>

  <!-- Video Background -->
  <div class="container vid-mar-remove position-relative mb-4">
    <div class="vid-parent">
      <video playsinline autoplay muted loop>
        <source src="video/215500.mp4">
      </video>
      <div class="vid-overlay"></div>
    </div>
    <div class="vid-content">
      <h1 class="my-content">Welcome to ShekharJagat</h1>
      <small class="my-content">Learn and Implement</small><br>
      <?php 
        if(isset($loginstatus) && $loginstatus==true){
          echo '<a class="nav-link btn btn-primary mt-3" href="student/studentprofile.php" style="width:100px;">Myprofile</a>';
        } else { 
          echo '<a href="#" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#RegModal">Get Started</a>';
        }
      ?>
    </div>
  </div>

  <!-- Text Banner -->
  <div class="container-fluid bg-danger txt-banner py-2">
    <div class="row text-center">
      <div class="col-6 col-md"><h5><i class="fa-solid fa-book"></i> 100+ Online Courses</h5></div>
      <div class="col-6 col-md"><h5><i class="fa-solid fa-users"></i> Expert Instructors</h5></div>
      <div class="col-6 col-md"><h5><i class="fa-solid fa-keyboard"></i> Life Time Access</h5></div>
      <div class="col-6 col-md"><h5><i class="fa-solid fa-bangladeshi-taka-sign"></i> Money Back Guarantee*</h5></div>
    </div>
  </div>

  <!-- Popular Courses -->
  <h2 class="text-center mt-4">Popular Courses</h2>
  <?php include('./course.php'); ?>
  <div class="text-center mt-3 mb-4">
    <a href="allcourse.php" class="btn btn-danger">View all</a>
  </div>

  <!-- Student Feedback -->
  <?php include('stu_feedback.php'); ?>

  <!-- Contact Form -->
  <?php include('./contact.php'); ?>

  <!-- Footer -->
  <?php include('foter.php'); ?>

  <!-- Student Registration Modal -->
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
              <label><i class="fa-solid fa-user me-2"></i>Name:</label>
              <input type="text" class="form-control" id="stuname" placeholder="Name" required>
              <small id="stunamemsg"></small>
            </div>
            <div class="mb-3">
              <label><i class="fa-solid fa-envelope me-2"></i>Email:</label>
              <input type="email" class="form-control" id="stuemail" placeholder="Email" required>
              <small id="stuemailmsg"></small>
            </div>
            <div class="mb-3">
              <label><i class="fa-solid fa-key me-2"></i>Password:</label>
              <input type="password" class="form-control" id="stupass" placeholder="Password" required>
              <small id="stupassmsg"></small>
            </div>
            <div class="mb-3 text-center">
              <button type="button" class="btn btn-danger w-100" id="firebase-google-signup-index" style="background-color:#db4437;">
                <i class="fab fa-google"></i> Continue with Google
              </button>
            </div>
            <div class="text-center mb-3"><span style="color:#999;">OR</span></div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-primary" id="signup">Sign up</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:black;">Close</button>
            </div>
            <div id="showmsg" class="mt-3 text-center"></div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Student Login Modal -->
  <div class="modal fade" id="LoginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Student Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="loginForm">
            <div class="mb-3">
              <label><i class="fa-solid fa-envelope me-2"></i>Email:</label>
              <input type="email" class="form-control" id="stulogemail" placeholder="Email" required>
            </div>
            <div class="mb-3">
              <label><i class="fa-solid fa-key me-2"></i>Password:</label>
              <input type="password" class="form-control" id="stulogpass" placeholder="Password" required>
            </div>
            <div class="mb-3 text-center">
              <button type="button" class="btn btn-danger w-100" id="firebase-google-login-index" style="background-color:#db4437;">
                <i class="fab fa-google"></i> Continue with Google
              </button>
            </div>
            <div class="text-center mb-3"><span style="color:#999;">OR</span></div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-primary" id="stulog">Login</button>
              <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
            <div id="logshowmsg" class="mt-3 text-center"></div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Admin Login Modal (Unchanged) -->
  <div class="modal fade" id="adminLoginModal" tabindex="-1" aria-labelledby="adminLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminLoginLabel">Admin Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="adminLoginForm">
            <div class="mb-3">
              <label for="admin-email" class="form-label"><i class="fa-solid fa-envelope me-2"></i>Email:</label>
              <input type="email" class="form-control" id="admin-email" placeholder="Email" required>
            </div>
            <div class="mb-3">
              <label for="admin-pass" class="form-label"><i class="fa-solid fa-key me-2"></i>Password:</label>
              <input type="password" class="form-control" id="admin-pass" placeholder="Password" required>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-primary" id="admin-login">Login</button>
              <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="js/all.min.js"></script>
  <script src="js/ajaxrequest.js"></script>
  <script src="js/adminajax.js"></script>
  <script src="js/firebase-auth.js"></script>

  <script>
    $(document).ready(function(){
      $('#testimonial-slider').owlCarousel({
        loop:true, margin:10, nav:true, dots:true, autoplay:true, autoplayTimeout:3000,
        responsive:{0:{items:1}, 600:{items:2}, 1000:{items:3}}
      });
    });

    // Admin login
    $('#admin-login').click(function(){
      let email = $('#admin-email').val();
      let pass = $('#admin-pass').val();
      if(email && pass){
        $.post("adminlogin.php", {email:email, pass:pass}, function(data){
          alert(data); // Replace with your response handling
        });
      } else {
        alert("Please fill all fields.");
      }
    });
  </script>

</body>
</html>
