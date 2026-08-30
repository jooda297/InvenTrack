<?php
session_start();
include "../Connect.php";

$S_ID = $_SESSION['S_Log'] ?? null;

if (!$S_ID) {
    header("Location: ../Login.php");
    exit;
}

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Invalid product");
}
// Build base URL dynamically (works for both localhost and IP)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host   = $_SERVER['HTTP_HOST']; // e.g. localhost OR 192.168.1.18
$baseURL = $scheme . "://" . $host . "/Inventrack";


$product_id = (int)$_GET['product_id'];

// (مهم) جيب المنتج وتأكد انه تابع لنفس السيلر
$stmt = $con->prepare("SELECT id, seller_id, name, price, qty, image, description
                       FROM products
                       WHERE id = ? AND active = 1 LIMIT 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$productRes = $stmt->get_result();

if ($productRes->num_rows === 0) {
    die("Product not found");
}

$product = $productRes->fetch_assoc();

// إذا بدك تمنع سيلر يشوف منتج سيلر ثاني:
if ((int)$product['seller_id'] !== (int)$S_ID) {
    die("Not allowed");
}

$successMsg = '';
$errorMsg = '';

// RESTOCK
if (isset($_POST['restock_submit'])) {
    $add_qty = (int)($_POST['add_qty'] ?? 0);

    if ($add_qty <= 0) {
        $errorMsg = "Enter a valid quantity to add.";
    } else {
        $stmt2 = $con->prepare("UPDATE products SET qty = qty + ? WHERE id = ? LIMIT 1");
        $stmt2->bind_param("ii", $add_qty, $product_id);
        $stmt2->execute();

        $successMsg = "Restocked successfully (+$add_qty).";

        // Refresh product data
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    }
}
$qrLink  = $baseURL . "/qr.php?pid=" . $product_id;
$qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($qrLink);

$imageUrl = $baseURL . "/Seller_Dashboard/" . ltrim($product['image'], "/");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">


    <link href="../assets/img/icon.png" rel="icon" />
    <link href="../assets/img/icon.png" rel="apple-touch-icon" />

  <meta charset="UTF-8">
  <title>Scanned Product - Seller</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Scanned Product</h3>
    <a class="btn btn-secondary" href="./Products.php">Back to Products</a>
  </div>

  <?php if ($successMsg): ?>
    <div class="alert alert-success"><?php echo $successMsg; ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <div class="row g-4">
        <div class="col-md-4 text-center">
          <?php if (!empty($product['image'])): ?>
           <img src="<?php echo $imageUrl; ?>" class="img-fluid rounded mb-3" alt="">
          <?php endif; ?>

          <div class="mb-2 fw-bold">Product QR</div>
          <img src="<?php echo $qrImage; ?>" alt="QR" class="img-fluid" style="max-width:220px;">
          <div class="mt-2">
            <a class="btn btn-outline-primary btn-sm" target="_blank" href="<?php echo $qrImage; ?>">Open QR Image</a>
          </div>
          <div class="mt-2 small text-muted">
            QR opens: <?php echo htmlspecialchars($qrLink); ?>
          </div>
        </div>

        <div class="col-md-8">
          <h4><?php echo htmlspecialchars($product['name']); ?></h4>
          <p class="text-muted mb-2"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>

          <div class="row">
            <div class="col-sm-4">
              <div class="p-3 border rounded bg-white">
                <div class="text-muted">Price</div>
                <div class="fw-bold"><?php echo htmlspecialchars($product['price']); ?> JOD</div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="p-3 border rounded bg-white">
                <div class="text-muted">Current Qty</div>
                <div class="fw-bold"><?php echo (int)$product['qty']; ?></div>
              </div>
            </div>
          </div>

          <hr>

          <h5>Restock</h5>
          <form method="POST">
            <div class="row g-2 align-items-end">
              <div class="col-sm-4">
                <label class="form-label">Add Quantity</label>
                <input type="number" name="add_qty" class="form-control" min="1" required>
              </div>
              <div class="col-sm-4">
                <button type="submit" name="restock_submit" class="btn btn-primary w-100">
                  Add to Stock
                </button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
