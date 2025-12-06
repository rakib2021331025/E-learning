<?php
session_start();
include "../dbconnection.php";

// Admin email fixed
$admin_email = "rakibhasan83012@gmail.com";

// Get student email from GET parameter
$student_email = $_GET['student'] ?? die("No student selected");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Chat with <?php echo $student_email; ?></title>
<link rel="stylesheet" href="../chat/style.css">
</head>
<body>

<h2>Chat with <?php echo htmlspecialchars($student_email); ?></h2>

<div class="chat-container">
    <div id="chatBox" class="chat-box"></div>
</div>

<div class="chat-controls">
    <form id="chatForm" enctype="multipart/form-data">
        <input type="hidden" name="sender" value="<?php echo $admin_email; ?>">
        <input type="hidden" name="receiver" value="<?php echo $student_email; ?>">

        <input type="text" name="message" placeholder="Type your message..." required>
        <input type="file" name="file" accept="image/*,audio/*,.pdf,.doc,.docx,.txt">
        <button type="submit" class="send-btn">Send</button>
        <button type="button" id="clearChat" class="clear-btn">Clear Chat</button>
    </form>

    <button id="recStart">🎤 Record</button>
    <button id="recStop" style="display:none;">⏹ Stop</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../chat/admin_chat.js"></script>

</body>
</html>
