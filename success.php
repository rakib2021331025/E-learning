<?php
include('./dbconnection.php');
session_start();

// Check login (optional but recommended)
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo "<script>location.href='loginorsignup.php';</script>";
    exit();
}   

// Check if val_id is received via POST (SSLCommerz sends this on success)
if (!isset($_POST['val_id']) || empty($_POST['val_id'])) {
    echo "⚠️ No payment validation data received.";
    exit();
}

$val_id = urlencode($_POST['val_id']);
$store_id = urlencode("elear688cf1cfe0edc");
$store_passwd = urlencode("elear688cf1cfe0edc@ssl");

// SSLCommerz validation URL (sandbox)
$validation_url = "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=$val_id&store_id=$store_id&store_passwd=$store_passwd&v=1&format=json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $validation_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable in local/testing; enable in production
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable in local/testing; enable in production

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($http_code == 200 && !curl_errno($ch)) {
    $result = json_decode($response);

    // Get and sanitize data from response
    $tran_id = $result->tran_id ?? '';
    $status = $result->status ?? '';
    $tran_date = $result->tran_date ?? '';
    $amount = $result->amount ?? 0;

    // Retrieve saved session data for order insertion
    $course_id = $_SESSION['course_id'] ?? '';
    $stu_name = $_SESSION['stu_name'] ?? '';
    $stu_email = $_SESSION['stu_email'] ?? '';

    // Check if required data is available
    if (empty($tran_id) || empty($course_id) || empty($stu_name) || empty($stu_email)) {
        echo "❌ Missing required data to insert order.";
        exit();
    }

    // Escape variables for SQL safety
    $tran_id_esc = $conn->real_escape_string($tran_id);
    $course_id_esc = (int)$course_id;
    $stu_name_esc = $conn->real_escape_string($stu_name);
    $stu_email_esc = $conn->real_escape_string($stu_email);
    $status_esc = $conn->real_escape_string($status);
    $tran_date_esc = $conn->real_escape_string($tran_date);
    $amount_esc = floatval($amount);

    // Insert order only if payment is successful or validated
    if ($status === "VALID" || $status === "VALIDATED") {
        $sql = "INSERT INTO course_order (tran_id, course_id, stu_name, stu_email, stat, order_date, amount)
                VALUES ('$tran_id_esc', $course_id_esc, '$stu_name_esc', '$stu_email_esc', '$status_esc', '$tran_date_esc', $amount_esc)";

        if ($conn->query($sql) === TRUE) {
            echo "<h3>✅ Payment Successful and Order Recorded!</h3>";
            echo "Transaction ID: $tran_id<br>";
            echo "Course ID: $course_id<br>";
            echo "Amount Paid: $amount BDT<br>";
            // Redirect to My Courses page after 3 seconds
            echo '<script>
                    setTimeout(() => {
                        window.location.href = "student/mycourse.php";
                    }, 3000);
                  </script>';
        } else {
            echo "❌ Insert Error: " . $conn->error;
        }
    } else {
        echo "❌ Payment validation failed. Status: $status";
    }
} else {
    echo "❌ Failed to connect with SSLCommerz validation server.<br>";
    echo "cURL Error: " . curl_error($ch);
}

curl_close($ch);
?>
