<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Owl Carousel CSS -->
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
  <style>
    body {
      background-color: #487289;
      color: white;
      font-family: Arial, sans-serif;
    }
    .testimonial {
      background-color: rgba(255, 0, 0, 0.6);
      padding: 20px;
      border-radius: 15px;
      margin: 10px;
    }
    .testimonial .description {
      font-style: italic;
      font-size: 15px;
    }
    .testimonial .testimonial-proof h5 {
      font-weight: bold;
    }
    .testimonial .pic img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      
    }
  </style>
</head>
<body>
<?php include('navbar.php');?>
<div class="container-fluid mt-5" id="feedback">
  <h2 class="text-center mb-4"><br>What Our Students Say</h2>
  <div class="row">
    <div class="col-md-12">
      <!-- Carousel container OUTSIDE the loop -->
      <div id="testimonial-slider" class="owl-carousel text-center">

        <?php
        include('dbconnection.php');
        $sql = "SELECT stu_name, stu_occ, stu_img, f_content FROM student AS s JOIN feedback AS f ON s.stu_id = f.stu_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $imgPath = str_replace('..', '.', $row['stu_img']);
        ?>
            <!-- Each testimonial INSIDE the loop -->
            <div class="testimonial">
              <p class="description"><?php echo $row['f_content']; ?></p>
              <div class="pic my-3">
                <img src="<?php echo $imgPath; ?>" class="rounded-circle" alt="<?php echo htmlspecialchars($row['stu_name']); ?>" style="margin-left:180px;">
              </div>
              <div class="testimonial-proof">
                <h5 class="mb-0"><?php echo htmlspecialchars($row['stu_name']); ?></h5>
                <small><?php echo htmlspecialchars($row['stu_occ']); ?></small>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<p class='text-center text-white'>No feedback available yet.</p>";
        }
        ?>

      </div>
    </div>
  </div>
</div>
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




<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Initialize Owl Carousel -->
<script>
  $(document).ready(function(){
    $('#testimonial-slider').owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      dots: true,
      autoplay: true,
      autoplayTimeout: 3000,
      responsive:{
        0:{ items:1 },
        600:{ items:2 },
        1000:{ items:3 }
      }
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
