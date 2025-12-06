<?php
session_start();
include '../dbconnection.php';
include 'studentinclude/header.php';

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

/* Responsive for Android/Mobile */
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
    
    .live-class-info {
        padding: 10px;
        font-size: 14px;
    }
}

@media screen and (max-width: 600px) {
    .live-class-iframe {
        height: 300px;
        min-height: 300px;
    }
}

.live-class-info {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>

<div class="content-area">
    <div class="live-class-container">
        <div class="live-class-header">
            <h3 class="mb-0">
                <i class="fa-solid fa-video"></i> Live Class Session
            </h3>
            <small>Room: <?php echo $room; ?></small>
        </div>
        
        <div class="live-class-info">
            <p class="mb-2">
                <i class="fas fa-info-circle text-primary"></i> 
                <strong>Instructions:</strong> Make sure your microphone and camera are enabled for the best experience.
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
</div>

<?php include 'studentinclude/footer.php'; ?>
