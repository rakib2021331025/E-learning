<?php
include "../dbconnection.php";
session_start();

// Check for multiple session variable names (support different login methods)
$logged_in_email = $_SESSION['stu_email'] ?? $_SESSION['stulogEmail'] ?? $_SESSION['stuLogEmail'] ?? '';

if(empty($logged_in_email) || (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true)){
    die("Please login first!");
}

$sender = $_POST['sender'];
$receiver = $_POST['receiver'];

// Verify that sender matches logged-in student
if($sender !== $logged_in_email){
    die("Unauthorized access!");
}

$message = $_POST['message'] ?? '';
$file_path = '';

// File upload (image/pdf/doc/audio)
if(isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE){
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','pdf','doc','docx','txt','mp3','wav','ogg','webm','m4a'];
    if(in_array($ext,$allowed)){
        $fileName = time().'_'.bin2hex(random_bytes(6)).'.'.$ext;
        $targetDir = "../chat/uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir,0755,true);
        $targetFile = $targetDir.$fileName;
        if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFile)){
            $file_path="chat/uploads/".$fileName;
        }
    }
}

$stmt=$conn->prepare("INSERT INTO chat_messages(sender_email,receiver_email,message,file_path) VALUES(?,?,?,?)");
$stmt->bind_param("ssss",$sender,$receiver,$message,$file_path);
$stmt->execute();
?>