<?php
session_start();
include '../dbconnection.php';
include './admininclude/header.php';

// Admin session check
$adminemail = $_SESSION['adminemail'] ?? $_SESSION['adminEmail'] ?? '';

if(empty($adminemail) || !isset($_SESSION['loginstatus'])){
    header("Location: addadmin.php");
    exit();
}

if(!isset($_GET['room'])){
    die("Room parameter missing!");
}

$room = htmlspecialchars($_GET['room']);
?>

<style>
.live-class-container {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px;
}

.live-class-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 25px;
    border-radius: 10px 10px 0 0;
    margin: -20px -20px 20px -20px;
}

.live-class-iframe {
    width: 100%;
    height: calc(100vh - 200px);
    min-height: 600px;
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.live-class-info {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive for Android */
@media screen and (max-width: 768px) {
    .live-class-container {
        padding: 10px;
        margin: 10px;
    }
    
    .live-class-iframe {
        height: calc(100vh - 150px);
        min-height: 400px;
    }
    
    .live-class-header {
        padding: 10px 15px;
        font-size: 14px;
    }
    
    .live-class-header h3 {
        font-size: 18px !important;
    }
    
    .live-class-info {
        padding: 10px;
        font-size: 14px;
    }
}

@media screen and (max-width: 600px) {
    .live-class-iframe {
        height: calc(100vh - 120px);
        min-height: 300px;
    }
}

@media screen and (max-height: 500px) and (orientation: landscape) {
    .live-class-iframe {
        height: calc(100vh - 100px);
    }
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="live-class-container">
        <div class="live-class-header">
            <h3 class="mb-0">
                <i class="fa-solid fa-video"></i> Live Class Session (Admin)
            </h3>
            <small>Room: <?php echo $room; ?></small>
        </div>
        
        <div class="live-class-info">
            <p class="mb-2">
                <i class="fas fa-info-circle text-primary"></i> 
                <strong>Instructions:</strong> You are joining as Admin/Teacher. Make sure your microphone and camera are enabled.
            </p>
            <p class="mb-0">
                <i class="fas fa-sign-in-alt text-success"></i> 
                You will be automatically joined to the live class room.
            </p>
        </div>
        
        <iframe 
            class="live-class-iframe"
            src="https://meet.jit.si/<?php echo $room; ?>" 
            allow="camera; microphone; fullscreen; speaker; display-capture"
            allowfullscreen>
        </iframe>
    </div>
</main>

