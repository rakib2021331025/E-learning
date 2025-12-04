<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../dbconnection.php');

$stulogEmail = $_SESSION['stu_email'];

$sql = "SELECT co.tran_id, c.course_id, c.course_name, c.course_duration, c.course_desc, c.course_img, c.course_author, c.course_orginal_price, c.course_price
        FROM course_order co
        JOIN course c ON co.course_id = c.course_id
        WHERE co.stu_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $stulogEmail);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid mt-4">
  <div class="row">
    
    <!-- Sidebar -->
    <div class="col-md-3">
        <?php include('./studentinclude/header.php'); ?>
    </div>

    <!-- Enrolled Course Area -->
    <div class="col-md-9">
      <h4 class="mb-4"><br>My Enrolled Courses</h4>
      <div class="row">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
              <div class="card h-100 shadow">
                <img src="<?php echo htmlspecialchars(str_replace('..', '..', $row['course_img'])); ?>" class="card-img-top" alt="Course Image">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($row['course_name']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($row['course_desc']); ?></p>
                  <p>
                    <del>৳<?php echo htmlspecialchars($row['course_orginal_price']); ?></del>
                    <span class="text-success fw-bold ms-2">৳<?php echo htmlspecialchars($row['course_price']); ?></span>
                  </p>
                </div>
                <div class="card-footer">
                  <small><?php echo htmlspecialchars($row['course_duration']); ?> | Level: Beginner</small>
                </div>
                <div class="p-3">
                  <a href="watchcourse.php?course_id=<?php echo $row['course_id']; ?>" class="btn btn-primary w-100">Watch Course</a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-danger">No enrolled courses found.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div> 