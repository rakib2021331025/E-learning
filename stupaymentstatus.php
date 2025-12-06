<?php
session_start();
include('./dbconnection.php');
include('./navbar.php');

// Login check
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo "<script>location.href='loginorsignup.php';</script>";
    exit();
}

$stu_email = $_SESSION['stulogEmail'];

$sql = "SELECT o.*, c.course_name 
        FROM course_order o 
        JOIN course c ON o.course_id = c.course_id 
        WHERE o.stu_email = '$stu_email' 
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Payment Status</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5" id="printArea">
  <h2 class="mb-4"> My Payment Status</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Transaction ID</th>
          <th>Course Name</th>
          <th>Amount (BDT)</th>
          <th>Status</th>
          <th>Order Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['tran_id']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td><?= number_format($row['amount'], 2) ?></td>
            <td>
              <?php if ($row['stat'] === 'VALID' || $row['stat'] === 'VALIDATED'): ?>
                <span class="badge bg-success">Successful</span>
              <?php else: ?>
                <span class="badge bg-danger"><?= htmlspecialchars($row['stat']) ?></span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['order_date']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="mt-4">
      <button onclick="window.print()" class="btn btn-primary"> Print</button>
      <button onclick="downloadPDF()" class="btn btn-success"> Download PDF</button>
    </div>

  <?php else: ?>
    <div class="alert alert-warning"> No payment records found.</div>
  <?php endif; ?>
</div>

<script>
function downloadPDF() {
  const element = document.getElementById('printArea');
  html2pdf()
    .from(element)
    .set({
      margin: 0.5,
      filename: 'My_Payment_Status.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    })
    .save();
}
</script>

</body>
</html>