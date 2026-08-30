<?php
session_start();
include "../Connect.php";

$S_ID = $_SESSION['S_Log'] ?? null;
if (!$S_ID) {
    header("Location: ../Login.php");
    exit;
}

$sql = mysqli_query($con, "SELECT id, name, qty FROM products WHERE seller_id = '$S_ID' AND active = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Product QR Codes</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .qr-card { border:1px solid #ddd; border-radius:12px; padding:12px; background:#fff; }
    .qr-img { width:140px; height:140px; object-fit:contain; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">All Product QR Codes</h3>
    <div>
      <button class="btn btn-outline-secondary" onclick="window.print()">Print</button>
      <a class="btn btn-secondary" href="./Products.php">Back</a>
    </div>
  </div>

  <div class="row g-3">
    <?php while($p = mysqli_fetch_array($sql)): 
      $pid = (int)$p['id'];
      $qrLink = "http://localhost/Inventrack/qr.php?pid=" . $pid;
      $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrLink);
    ?>
      <div class="col-md-4">
        <div class="qr-card">
          <div class="fw-bold"><?php echo htmlspecialchars($p['name']); ?></div>
          <div class="text-muted">Qty: <?php echo (int)$p['qty']; ?> | ID: <?php echo $pid; ?></div>
          <img class="qr-img mt-2" src="<?php echo $qrImage; ?>" alt="QR">
          <div class="small mt-2 text-muted"><?php echo htmlspecialchars($qrLink); ?></div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
