

<?php
include('./admininclude/header.php');
include('../dbconnection.php');

// 🛡️ আগেই সব ভ্যারিয়েবল initialize
$stu_name = $stu_email = $stu_pass= $stu_occ = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stu_name = $_POST['stu_name'];
    $stu_email = $_POST['stu_email'];
    $stu_pass = $_POST['stu_pass'];
    $stu_occ = $_POST['stu_occ'];

    $sql = "INSERT INTO student 
        (stu_name, stu_email, stu_pass, stu_occ) VALUES
        ('$stu_name', '$stu_email', '$stu_pass', '$stu_occ')";

    if ($conn->query($sql) === TRUE) {
        $msg = '<div class="alert alert-success col-sm-6">Student added Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger col-sm-6">Student upload Failed: ' . $conn->error . '</div>';
    }
}
?>


<div class="container mt-5" style="margin-left:300px;width:600px;"> <!-- Adjust this if your sidebar is wider -->
  <div class="card p-4 shadow"style="background-color:#256789;color:white;">
    <h4 class="text-center mb-4">Add New Students</h4>
    
    <form action="" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="stu_name" class="form-label">Student Name</label>
        <input type="text" class="form-control" id="stu_name" name="stu_name" required>
      </div>

      <div class="mb-3">
        <label for="stu_email" class="form-label">Student Email</label>
        <input class="form-control" id="stu_email" name="stu_email" required> 
      </div>
         <div class="mb-3">
        <label for="stu_email" class="form-label">Student password</label>
        <input class="form-control" id="stu_pass" name="stu_pass" required> 
      </div>

     
      <div class="mb-3">
        <label for="" class="form-label">occupation</label>
        <input type="text" class="form-control" id="stu_occ" name="stu_occ" required>
      </div>

      <div class="mb-3">
      <div class="d-flex justify-content-start gap-2">
        <button type="submit" class="btn btn-success">Submit</button>
        <a type="reset" class="btn btn-secondary" onclick="clearImagePreview()">Close</a>
      </div>
      <?php 
      if(isset($msg)) echo $msg;?>
  
    </form>
  </div>
</div>

