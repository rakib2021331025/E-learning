


<?php
include('dbconnection.php');

// Ensure contact table exists
$createTableSql = "CREATE TABLE IF NOT EXISTS contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contactname VARCHAR(255) NOT NULL,
    contactsubject VARCHAR(255) NULL,
    contactemail VARCHAR(255) NOT NULL,
    contactdesc TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTableSql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactname = $_POST['contactname'] ?? '';
    $contactsubject = $_POST['contactsubject'] ?? '';
    $contactemail = $_POST['contactemail'] ?? '';
    $contactdesc = $_POST['contactdesc'] ?? '';

    if (!empty($contactname) && !empty($contactemail) && !empty($contactdesc)) {
        $contactname = mysqli_real_escape_string($conn, $contactname);
        $contactsubject = mysqli_real_escape_string($conn, $contactsubject);
        $contactemail = mysqli_real_escape_string($conn, $contactemail);
        $contactdesc = mysqli_real_escape_string($conn, $contactdesc);

        $sql = "INSERT INTO contact (contactname, contactsubject, contactemail, contactdesc)
                VALUES ('$contactname', '$contactsubject', '$contactemail', '$contactdesc')";

        if (mysqli_query($conn, $sql)) {
            echo "<p style='color:green;'>Thank you for your message!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill all required fields.</p>";
    }
}
?>

