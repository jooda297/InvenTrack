<?php
session_start();
include "../Connect.php";

function notify_socket_product_updated($host, $product_id, $new_qty, $seller_id) {
  $payload = json_encode([
    "product_id" => (int)$product_id,
    "new_quantity" => (int)$new_qty,
    "seller_id" => (int)$seller_id
  ]);

  $ch = curl_init("http://{$host}:3000/product-updated");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 2); // don't freeze page
  curl_exec($ch);
  curl_close($ch);
}


$S_ID = $_SESSION['S_Log'] ?? null;

if (!$S_ID) {
  echo '<script>document.location="../Login.php";</script>';
  exit;
}

// seller info
$sqlUser = mysqli_query($con, "SELECT * FROM users WHERE id='$S_ID' LIMIT 1");
$user = mysqli_fetch_assoc($sqlUser);

$name  = $user['name'] ?? '';
$email = $user['email'] ?? '';
$image = $user['image'] ?? '';

// Handle reorder submit (ADD quantity)
if (isset($_POST['SubmitReorder'])) {

  $product_id   = (int)($_POST['product_id'] ?? 0);
  $reorder_qty  = (int)($_POST['reorder_qty'] ?? 0);

  if ($product_id > 0 && $reorder_qty > 0) {

    // get current qty safely (and make sure product belongs to this seller)
    $stmt1 = $con->prepare("SELECT qty FROM products WHERE id = ? AND seller_id = ? LIMIT 1");
    $stmt1->bind_param("ii", $product_id, $S_ID);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $row1 = $res1->fetch_assoc();

    if ($row1) {
      $current_qty = (int)($row1['qty'] ?? 0);
      $new_qty = $current_qty + $reorder_qty;

      $out_of_stock = ($new_qty > 0) ? 0 : 1;

      $stmt2 = $con->prepare("UPDATE products SET qty = ? WHERE id = ? AND seller_id = ?");
$stmt2->bind_param("iii", $new_qty, $product_id, $S_ID);


      if ($stmt2->execute()) {

  // ✅ notify socket server so Products.php updates without refresh
  $host = $_SERVER['HTTP_HOST']; // works for localhost or IP
  notify_socket_product_updated($host, $product_id, $new_qty, $S_ID);

  echo "<script>alert('Reorder added! New QTY = {$new_qty}');</script>";
  echo "<script>document.location='./Reorder.php';</script>";
  exit;
}

    }
  }

  echo "<script>alert('Something went wrong.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Reorder Stock - Inventrack</title>

  <link href="../assets/img/icon.png" rel="icon" />
  <link href="../assets/img/icon.png" rel="apple-touch-icon" />

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet" />
  <link href="../assets/css/style.css" rel="stylesheet" />

  <style>
    /* make out-of-stock rows pop */
    tr.out-row td, tr.out-row th {
      background: #fff5f7 !important;
    }
    .badge-out {
      border: 1px solid #dc3545;
      color: #dc3545;
      background: #ffe3ea;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 12px;
      display: inline-block;
    }
    .badge-in {
      border: 1px solid #198754;
      color: #198754;
      background: #e8fff1;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 12px;
      display: inline-block;
    }
  </style>
</head>

<body>
        <style> 
/* Make header logo bigger */
.header .logo img {
    height: 75px !important;     /* adjust size here */
    width: auto !important;
    margin-bottom: 0px;
}

/* Optional: increase spacing around the logo */
.header .logo {
    display: flex;
    align-items: center;
    gap: 0px;
}



    </style>
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center">
      <img src="../assets/img/Logo.png" alt="" />
    </a>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo $image ?>" alt="Profile" class="rounded-circle" />
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $name ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $name ?></h6>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="./Logout.php">
              <i class="bi bi-box-arrow-right"></i><span>Sign Out</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>

<?php require './Aside-Nav/Aside.php'?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Reorder Stock</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item">Reorder Stock</li>
      </ol>
    </nav>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="reorderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reorder Quantity</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form method="POST" action="./Reorder.php">
            <input type="hidden" id="product_id" name="product_id">

            <div class="mb-2">
              <div class="small text-muted">Current QTY: <span id="current_qty_text">0</span></div>
              <div class="small text-muted">New QTY will be: <span id="new_qty_text">0</span></div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-4 col-form-label">Add QTY</label>
              <div class="col-sm-8">
                <input type="number" min="1" class="form-control" id="reorder_qty" name="reorder_qty" required>
              </div>
            </div>

            <div class="text-end">
              <button type="submit" name="SubmitReorder" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body pt-3">
            <table class="table datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Current QTY</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                // show ALL products for this seller
                $sql = mysqli_query($con, "SELECT id, name, qty, out_of_stock, created_at
                                          FROM products
                                          WHERE seller_id = '$S_ID'
                                          ORDER BY (qty <= 0) DESC, id DESC");

                while ($p = mysqli_fetch_assoc($sql)) {
                  $pid = (int)$p['id'];
                  $pname = $p['name'];
                  $qty = (int)($p['qty'] ?? 0);
                  $qty = (int)($p['qty'] ?? 0);
                  $isOut = ($qty <= 0);
                  $created = $p['created_at'];

                  $rowClass = $isOut ? "out-row" : "";
$badge = $isOut
  ? "<span class='badge-out'>Out of Stock</span>"
  : "<span class='badge-in'>In Stock</span>";

                  ?>
                <tr class="<?php echo $rowClass; ?>">
                  <th><?php echo $pid; ?></th>
                  <td><?php echo htmlspecialchars($pname); ?></td>
                  <td><?php echo $qty; ?></td>
                  <td><?php echo $badge; ?></td>
                  <td><?php echo $created; ?></td>
                  <td>
                    <button
                      class="btn btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#reorderModal"
                      onclick="openReorder(<?php echo $pid; ?>, <?php echo $qty; ?>)"
                    >
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </td>
                </tr>
              <?php }?> 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
function openReorder(productId, currentQty) {
  document.getElementById('product_id').value = productId;
  document.getElementById('current_qty_text').innerText = currentQty;
  document.getElementById('new_qty_text').innerText = currentQty;

  const input = document.getElementById('reorder_qty');
  input.value = '';
  input.oninput = () => {
    const add = parseInt(input.value || '0', 10);
    document.getElementById('new_qty_text').innerText = currentQty + (isNaN(add) ? 0 : add);
  };
}
</script>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
