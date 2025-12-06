<?php
include "../dbconnection.php";
session_start();

// Fixed default admin email
$admin_email = "rakibhasan83012@gmail.com";

$result = $conn->query("SELECT DISTINCT sender_email FROM chat_messages WHERE sender_email != '$admin_email'");

echo "<h2>Student List</h2>";
echo "<ul>";
while($row = $result->fetch_assoc()){
    $student = $row['sender_email'];
    echo "<li>
            <a href='chat.php?student={$student}'>{$student}</a>
          </li>";
}
echo "</ul>";
