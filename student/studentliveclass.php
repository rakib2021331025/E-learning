<?php
include '../dbconnection.php';
session_start();
include 'studentinclude/header.php';

$stulogEmail = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? '';
if(!$stulogEmail){
    echo "<div class='content-area'><div class='alert alert-danger'>Please login first</div></div>";
    exit;
}

// Fetch courses student enrolled in with course names
$sql = "SELECT co.course_id, c.course_name, c.course_desc 
        FROM course_order co 
        JOIN course c ON co.course_id = c.course_id 
        WHERE co.stu_email='$stulogEmail'";
$res = $conn->query($sql);

$has_live_classes = false;
?>

<style>
.live-class-wrapper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 70px);
    padding: 30px;
}

.live-class-header {
    color: white;
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: bold;
}

.live-class-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 25px;
    margin-bottom: 20px;
    transition: transform 0.3s;
}

.live-class-card:hover {
    transform: translateY(-5px);
}

.course-title {
    color: #667eea;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
}

.course-info {
    color: #666;
    margin-bottom: 15px;
}

.join-live-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-weight: bold;
    transition: all 0.3s;
    border: none;
}

.join-live-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
    text-decoration: none;
}

.no-class-card {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 10px;
    padding: 20px;
}

.no-enrollment {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}
</style>

<div class="content-area">
    <div class="live-class-wrapper" style="width: 90%; margin: 0 auto;">
        <h2 class="live-class-header">
            <i class="fa-solid fa-video"></i> Live Classes
        </h2>
        
        <?php if($res && $res->num_rows > 0): ?>
            <div class="row">
                <?php while($row = $res->fetch_assoc()): 
                    $course_id = $row['course_id'];
                    $course_name = $row['course_name'];
                    $course_desc = $row['course_desc'] ?? '';

                    // Fetch latest active live class for this course
                    $sql2 = "SELECT lc.*, c.course_name 
                             FROM live_classes lc
                             JOIN course c ON lc.course_id = c.course_id
                             WHERE lc.course_id='$course_id' AND lc.status='active' 
                             ORDER BY lc.id DESC LIMIT 1";
                    $res2 = $conn->query($sql2);

                    if($res2 && $res2->num_rows > 0):
                        $has_live_classes = true;
                        $live = $res2->fetch_assoc();
                        $room = $live['room_name'];
                ?>
                        <div class="col-md-6 mb-4">
                            <div class="live-class-card">
                                <h4 class="course-title">
                                    <i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($course_name); ?>
                                </h4>
                                <?php if($course_desc): ?>
                                    <p class="course-info"><?php echo htmlspecialchars($course_desc); ?></p>
                                <?php endif; ?>
                                <p class="course-info">
                                    <i class="fas fa-door-open"></i> Room: <strong><?php echo htmlspecialchars($room); ?></strong>
                                </p>
                                <p class="course-info">
                                    <i class="fas fa-clock"></i> Started: <?php echo date('d M Y, h:i A', strtotime($live['created_at'])); ?>
                                </p>
                                <a href="studentjoinclass.php?room=<?php echo $room; ?>" 
                                   target="_blank" 
                                   class="join-live-btn">
                                    <i class="fas fa-video"></i> Join Live Class
                                </a>
                            </div>
                        </div>
                <?php else: ?>
                        <div class="col-md-6 mb-4">
                            <div class="live-class-card no-class-card">
                                <h4 class="course-title">
                                    <i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($course_name); ?>
                                </h4>
                                <p class="text-muted">
                                    <i class="fas fa-info-circle"></i> No active live class for this course at the moment.
                                </p>
                            </div>
                        </div>
                <?php endif; ?>
                <?php endwhile; ?>
            </div>
            
            <?php if(!$has_live_classes): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> You are enrolled in courses, but there are no active live classes at the moment.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-enrollment">
                <h4><i class="fas fa-exclamation-triangle"></i> You are not enrolled in any course.</h4>
                <p class="mb-0">Please enroll in a course to join live classes.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'studentinclude/footer.php'; ?>
