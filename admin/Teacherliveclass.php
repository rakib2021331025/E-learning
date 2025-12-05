<?php
include '../dbconnection.php';
session_start();
include './admininclude/header.php';

// Admin session check
$adminemail = $_SESSION['adminemail'] ?? $_SESSION['adminEmail'] ?? $_SESSION['stuemail'] ?? '';

if(empty($adminemail) || !isset($_SESSION['loginstatus'])){
    header("Location: ../index.php");
    exit();
}

// Get admin_id from database if not in session
if(empty($_SESSION['admin_id'])){
    $admin_id_query = $conn->query("SELECT admin_id FROM admin_login WHERE admin_email='$adminemail' LIMIT 1");
    if($admin_id_query && $admin_id_query->num_rows > 0){
        $admin_row = $admin_id_query->fetch_assoc();
        $admin_id = $admin_row['admin_id'] ?? 1;
        $_SESSION['admin_id'] = $admin_id;
    } else {
        $admin_id = 1;
        $_SESSION['admin_id'] = 1;
    }
} else {
    $admin_id = $_SESSION['admin_id'];
}

// Fetch all courses
$sql = "SELECT * FROM course";
$courses = $conn->query($sql);

$success_msg = "";
$error_msg = "";

// Start Live Class
if(isset($_POST['start_class'])){
    $course_id = intval($_POST['course_id']);
    
    // Check if live_classes table exists, create if not
    $check_table = $conn->query("SHOW TABLES LIKE 'live_classes'");
    if($check_table->num_rows == 0){
        $create_table = "CREATE TABLE IF NOT EXISTS live_classes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            admin_id INT NOT NULL,
            admin_email VARCHAR(255),
            room_name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(50) DEFAULT 'active'
        )";
        $conn->query($create_table);
    }
    
    // Generate unique room name
    $room_name = "class_" . $course_id . "_" . time();
    
    // Insert live class
    $stmt = $conn->prepare("INSERT INTO live_classes (course_id, admin_id, admin_email, room_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $course_id, $admin_id, $adminemail, $room_name);

    if($stmt->execute()){
        $success_msg = "Live class started successfully! Room: " . $room_name;
    } else {
        $error_msg = "Error starting live class: " . $stmt->error;
    }
    $stmt->close();
}
?>

<style>
.live-class-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 40px);
    padding: 30px;
}

.live-class-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 30px;
    margin-bottom: 20px;
}

.live-class-header {
    color: #667eea;
    font-weight: bold;
    margin-bottom: 25px;
    font-size: 28px;
}

.btn-live-start {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    color: white;
    transition: transform 0.2s;
}

.btn-live-start:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.class-list-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
}

.class-list-card h5 {
    color: #333;
    margin-bottom: 10px;
}

.join-btn {
    background: #28a745;
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.join-btn:hover {
    background: #218838;
    transform: translateX(5px);
    color: white;
    text-decoration: none;
}

.form-select {
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    padding: 10px;
    font-size: 15px;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="live-class-container">
        <div class="container">
            <h2 class="live-class-header">
                <i class="fa-solid fa-video"></i> Live Class Management
            </h2>
            
            <?php if($success_msg): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="live-class-card">
                <h4 class="mb-4"><i class="fas fa-plus-circle"></i> Start New Live Class</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="course" class="form-label fw-bold">Select Course:</label>
                        <select name="course_id" id="course" class="form-select" required>
                            <option value="">-- Select a Course --</option>
                            <?php
                            if($courses && $courses->num_rows > 0){
                                while($row = $courses->fetch_assoc()){
                                    echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                                }
                            } else {
                                echo "<option value=''>No courses available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="start_class" class="btn btn-live-start">
                        <i class="fa-solid fa-video"></i> Start Live Class
                    </button>
                </form>
            </div>
            
            <div class="live-class-card">
                <h4 class="mb-4"><i class="fas fa-list"></i> Active Live Classes</h4>
                <?php
                $active_classes = $conn->query("
                    SELECT lc.*, c.course_name 
                    FROM live_classes lc 
                    JOIN course c ON lc.course_id = c.course_id 
                    WHERE lc.status = 'active' 
                    ORDER BY lc.created_at DESC
                ");
                
                if($active_classes && $active_classes->num_rows > 0):
                ?>
                    <div class="row">
                        <?php while($class = $active_classes->fetch_assoc()): ?>
                            <div class="col-md-6 mb-3">
                                <div class="class-list-card">
                                    <h5><i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($class['course_name']); ?></h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-door-open"></i> Room: <strong><?php echo htmlspecialchars($class['room_name']); ?></strong>
                                    </p>
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-clock"></i> Started: <?php echo date('d M Y, h:i A', strtotime($class['created_at'])); ?>
                                    </p>
                                    <a href="adminjoinclass.php?room=<?php echo urlencode($class['room_name']); ?>" 
                                       target="_blank" class="join-btn">
                                        <i class="fas fa-sign-in-alt"></i> Join Class
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No active live classes at the moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
