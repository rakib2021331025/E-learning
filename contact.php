<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <style>
    body{
      padding-top:70px;
    }
    </style>
</head>
<body>
    <?php
    include('dbconnection.php');
  include('./navbar.php');
  ?>

   <div class="container mt-4" id="Contact">
 <h2 class="text-center mb-4" style="color:white"> Contact us </h2>
 <div class="row">
  <div class="col-md-8">
    <form action="contactrequest.php" method="post" class="contact-form">
      <input type="text" class="form-control" name="contactname" placeholder="Name" id="contactname" required><br>
      <input type="text" class="form-control" name="contactsubject" placeholder="Subject" id="contactsubject"><br>
      <input type="email" class="form-control" name="contactemail" placeholder="Email" id="contactemail" required><br>
      <textarea class="form-control" name="contactdesc" placeholder="How we can help you?" style="height:150px;" id="contactdesc"></textarea>
      <button type="submit" value="send" id="send" class="btn btn-primary">send</button>
    </form>
  </div>
  <div class="col-md-4 stripe text-white text-center">
    <h4>ShekharJagat</h4>
    <p>Near BGB camp akhalia,sylhet<br>phone:01753952830<br>www.ShekharJagat.com</p>

  </div>
 </div>

</div>
<div class="container-fluid bg-danger txt-banner">
  <div class="row bottom-banner">
    <div class="col-sm">
      <h5><a href="#" class="icons"><i class="fa-brands fa-facebook" style="color:green;margin-right:10px;font-size:25px;"></i></a>facebook</h5>
    </div>
    <div class="col-sm">
      <h5><a href="#" class="icons"><i class="fa-brands fa-square-whatsapp" style="color:green;margin-right:10px;font-size:25px;"></i></a>whatsapp</h5>
    </div>
    <div class="col-sm">
      <h5><a href="#" class="icons"><i class="fa-brands fa-square-instagram" style="color:green;margin-right:10px;font-size:25px;"></i></a>Instragram</h5>
    </div>
        <div class="col-sm">
      <h5><a href="#" class="icons"><i class="fa-brands fa-square-instagram" style="color:green;margin-right:10px;font-size:25px;"></i></a>twitter</h5>
    </div>
    <div class="col-sm">
      <h5></h5>
    </div>

</div></div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.min.js"></script>

</body>
</html>