<?php
session_start();
include "../dbconnection.php";


$stuEmail = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($stuEmail) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}
$admin_email = "rakibhasan83012@gmail.com";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Chat with Admin</title>
<link rel="stylesheet" href="../chat/style.css">
</head>
<body>

<h2>Chat with Admin</h2>

<div class="chat-container">
    <div id="chatBox" class="chat-box"></div>
</div>

<div class="chat-controls">
    <form id="chatForm" enctype="multipart/form-data">
        <input type="hidden" name="sender" value="<?php echo $stuEmail; ?>">
        <input type="hidden" name="receiver" value="<?php echo $admin_email; ?>">

        <input type="text" name="message" placeholder="Type your message..." required>
        <input type="file" name="file" accept="image/*,audio/*,.pdf,.doc,.docx,.txt">
        <button type="submit">Send</button>
        <button type="button" id="clearChat">Clear Chat</button>
    </form>

    <button id="recStart">🎤 Record</button>
    <button id="recStop" style="display:none;">⏹ Stop</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../chat/student_chat.js"></script>

</body>
</html>
