<?php
session_start();
include '../dbconnection.php';
include './admininclude/header.php';

// Optional: Admin login check
if (!isset($_SESSION['adminEmail']) && !isset($_SESSION['adminemail'])) {
    // header("Location: addadmin.php");
    // exit();
}

// Fetch contact messages
$contacts = $conn->query("SELECT * FROM contact ORDER BY id DESC");
?>

<style>
.contact-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 40px);
    padding: 30px;
}

.contact-header {
    color: white;
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: bold;
}

.contact-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 20px 25px;
    margin-bottom: 20px;
}

.contact-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.contact-email {
    color: #555;
    font-size: 14px;
}

.contact-subject {
    font-weight: bold;
    margin-top: 10px;
    color: #667eea;
}

.contact-message {
    margin-top: 8px;
    color: #444;
    white-space: pre-wrap;
}

.badge-contact {
    background: #17a2b8;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.no-contact {
    background: white;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    color: #666;
}
</style>

<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 mt-5 pt-4">
    <div class="contact-container">
        <div class="container">
            <h2 class="contact-header">
                <i class="fas fa-envelope-open-text"></i> Contact Messages
            </h2>
            
            <?php if ($contacts && $contacts->num_rows > 0): ?>
                <?php while ($row = $contacts->fetch_assoc()): ?>
                    <div class="contact-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="contact-name">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($row['contactname']); ?>
                                </div>
                                <div class="contact-email">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($row['contactemail']); ?>
                                </div>
                            </div>
                            <span class="badge-contact">New</span>
                        </div>
                        
                        <?php if (!empty($row['contactsubject'])): ?>
                            <div class="contact-subject">
                                <i class="fas fa-tag"></i>
                                <?php echo htmlspecialchars($row['contactsubject']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contact-message">
                            <?php echo nl2br(htmlspecialchars($row['contactdesc'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-contact">
                    <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h4>No contact messages yet.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

