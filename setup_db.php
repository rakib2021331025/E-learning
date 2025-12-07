<?php
include 'dbconnection.php';
$sql = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    sender_id VARCHAR(255), 
    sender_name VARCHAR(255), 
    sender_type ENUM('student', 'admin'), 
    message TEXT, 
    media_url VARCHAR(255), 
    message_type ENUM('text', 'audio', 'video', 'image'), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if($conn->query($sql) === TRUE) { 
    echo "Table created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error . "\n"; 
}
?>
