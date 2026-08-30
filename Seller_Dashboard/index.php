<?php
session_start();
include "../Connect.php";

// ---------- AUTH: GET SELLER FROM SESSION ----------
$S_ID = isset($_SESSION['S_Log']) ? $_SESSION['S_Log'] : null;

if (!$S_ID) {
    header("Location: ../Login.php");
    exit;
}

// ---------- CARDS QUERIES ----------

// 1) PRODUCTS IN STOCK – active products that belong to this seller
$sqlInStock = mysqli_query(
    $con,
    "SELECT COUNT(id) AS in_stock 
     FROM products 
     WHERE seller_id = '$S_ID' AND active = 1"
);
$rowInStock = mysqli_fetch_assoc($sqlInStock);
$in_stock = (int)$rowInStock['in_stock'];

// 2) NUMBER OF ORDERS – only this seller, ignore canceled (status_id = 4)
$sqlOrders = mysqli_query(
    $con,
    "SELECT COUNT(DISTINCT orders.id) AS total_orders 
     FROM orders 
    INNER JOIN order_items ON order_items.order_id = orders.id
     WHERE order_items.seller_id = '$S_ID' AND orders.status_id != 4"
);
$rowOrders = mysqli_fetch_assoc($sqlOrders);
$total_orders = (int)$rowOrders['total_orders'];

// 3) TOTAL CASH – sum of total_price for this seller’s non-canceled orders
$sqlCash = mysqli_query(
    $con,
    "SELECT COALESCE(SUM(total_price),0) AS total_cash 
     FROM orders 
     INNER JOIN order_items ON order_items.order_id = orders.id
     WHERE order_items.seller_id = '$S_ID' AND orders.status_id != 4"
);
$rowCash = mysqli_fetch_assoc($sqlCash);
$total_cash = (float)$rowCash['total_cash']; // e.g. 15.00

// 4) PROFITS – for example 10% of total cash
$profit_percentage = 0.10; // change this if you want a different margin
$profits = $total_cash * $profit_percentage;

// formatted strings for cards
$display_total_cash = number_format($total_cash, 2);
$display_profits    = number_format($profits, 2);

// ---------- SELLER INFO FOR HEADER ----------
$sqlSeller = mysqli_query($con, "SELECT name, image FROM users WHERE id = '$S_ID'");
$sellerRow = mysqli_fetch_assoc($sqlSeller);
$seller_name  = $sellerRow ? $sellerRow['name']  : 'Seller';
$seller_image = $sellerRow ? $sellerRow['image'] : '../assets/img/default-avatar.png';

// ---------- CHART DATA ----------

// Orders & revenue per day (last 30 days)
$sqlOrdersChart = mysqli_query(
    $con,
    "SELECT DATE(o.created_at) AS order_day,
            COUNT(DISTINCT o.id) AS orders_count,
            COALESCE(SUM(oi.product_price * oi.quantity),0) AS revenue
     FROM orders o
     LEFT JOIN order_items oi ON oi.order_id = o.id
     WHERE oi.seller_id = '$S_ID'
     ANd o.status_id != 4
       AND o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
     GROUP BY DATE(o.created_at)
     ORDER BY order_day ASC"
);
$ordersLabels  = [];
$ordersCounts  = [];
$ordersRevenue = [];
while ($r = mysqli_fetch_assoc($sqlOrdersChart)) {
    $ordersLabels[]  = $r['order_day'];
    $ordersCounts[]  = (int)$r['orders_count'];
    $ordersRevenue[] = (float)$r['revenue'];
}

// Products created per day
$sqlProdCreated = mysqli_query(
    $con,
    "SELECT DATE(created_at) AS day, COUNT(*) AS count
     FROM products
     WHERE seller_id = '$S_ID'
     GROUP BY DATE(created_at)
     ORDER BY day ASC"
);
$prodCreatedLabels = [];
$prodCreatedCounts = [];
while ($r = mysqli_fetch_assoc($sqlProdCreated)) {
    $prodCreatedLabels[] = $r['day'];
    $prodCreatedCounts[] = (int)$r['count'];
}

// Products QTY report (sum qty per creation day)
$sqlQtyReport = mysqli_query(
    $con,
    "SELECT DATE(created_at) AS day, COALESCE(SUM(qty),0) AS total_qty
     FROM products
     WHERE seller_id = '$S_ID'
     GROUP BY DATE(created_at)
     ORDER BY day ASC"
);
$qtyLabels = [];
$qtyValues = [];
while ($r = mysqli_fetch_assoc($sqlQtyReport)) {
    $qtyLabels[]  = $r['day'];
    $qtyValues[]  = (int)$r['total_qty'];
}

// Active vs inactive products
$sqlActive = mysqli_query(
    $con,
    "SELECT
        SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) AS active_count,
        SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) AS inactive_count
     FROM products
     WHERE seller_id = '$S_ID'"
);
$rowActive         = mysqli_fetch_assoc($sqlActive);
$active_products   = (int)$rowActive['active_count'];
$inactive_products = (int)$rowActive['inactive_count'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Dashboard - Inventrack</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <link href="../assets/img/icon.png" rel="icon" />
  <link href="../assets/img/icon.png" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet"
  />

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet" />
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet" />
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
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/Logo.png" alt="Inventrack" />
      </a>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a
            class="nav-link nav-profile d-flex align-items-center pe-0"
            href="#"
            data-bs-toggle="dropdown"
          >
            <img src="<?php echo htmlspecialchars($seller_image); ?>" alt="Profile" class="rounded-circle" />
            <span class="d-none d-md-block dropdown-toggle ps-2">
              <?php echo htmlspecialchars($seller_name); ?>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($seller_name); ?></h6>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="./Logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php require './Aside-Nav/Aside.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Welcome card -->
            <div class="col-12">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title">Hello <?php echo htmlspecialchars($seller_name); ?> 👋</h5>
                  <p>
                    Here’s an overview of how your store is doing today.
                    Check your in-stock products, recent orders, total cash, and profits below.
                  </p>
                </div>
              </div>
            </div>

            <!-- In Stock -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title">IN STOCK</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="ps-1">
                      <h3><?php echo $in_stock; ?></h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- # Orders -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title"># Orders</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-bag-check"></i>
                    </div>
                    <div class="ps-1">
                      <h3><?php echo $total_orders; ?></h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Cash -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title"># Total Cash</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="ps-3">
                      <h3><?php echo $display_total_cash; ?> JODs</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profits -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title">Profits</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="ps-3">
                      <h3><?php echo $display_profits; ?> JODs</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ===== ROW 1 CHARTS ===== -->
            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Orders Report</h5>
                  <div id="ordersReport" style="min-height: 350px;"></div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Products Created Report</h5>
                  <div id="productsCreatedReport" style="min-height: 350px;"></div>
                </div>
              </div>
            </div>

            <!-- ===== ROW 2 CHARTS ===== -->
            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Products QTY Report</h5>
                  <div id="productsQtyReport" style="min-height: 350px;"></div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Active Products Report</h5>
                  <div id="activeProductsReport" style="min-height: 350px;"></div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Inventrack</span></strong>. All Rights Reserved
    </div>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/js/main.js"></script>

  <!-- Charts JS -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // PHP → JS data
      const ordersLabels   = <?php echo json_encode($ordersLabels); ?>;
      const ordersCounts   = <?php echo json_encode($ordersCounts); ?>;
      const ordersRevenue  = <?php echo json_encode($ordersRevenue); ?>;

      const prodLabels     = <?php echo json_encode($prodCreatedLabels); ?>;
      const prodCounts     = <?php echo json_encode($prodCreatedCounts); ?>;

      const qtyLabels      = <?php echo json_encode($qtyLabels); ?>;
      const qtyValues      = <?php echo json_encode($qtyValues); ?>;

      const activeProducts   = <?php echo (int)$active_products; ?>;
      const inactiveProducts = <?php echo (int)$inactive_products; ?>;

      // Orders Report
      if (document.querySelector("#ordersReport")) {
        new ApexCharts(document.querySelector("#ordersReport"), {
          series: [
            { name: 'Orders', data: ordersCounts },
            { name: 'Revenue (JOD)', data: ordersRevenue }
          ],
          chart: { type: 'line', height: 350, toolbar: { show: false } },
          stroke: { curve: 'smooth', width: 3 },
          markers: { size: 4 },
          dataLabels: { enabled: false },
          xaxis: { categories: ordersLabels },
          yaxis: { labels: { formatter: val => val.toFixed(0) } }
        }).render();
      }

      // Products Created Report
      if (document.querySelector("#productsCreatedReport")) {
        new ApexCharts(document.querySelector("#productsCreatedReport"), {
          series: [
            { name: 'Products Created', data: prodCounts }
          ],
          chart: { type: 'line', height: 350, toolbar: { show: false } },
          stroke: { curve: 'smooth', width: 3 },
          markers: { size: 4 },
          dataLabels: { enabled: false },
          xaxis: { categories: prodLabels },
          yaxis: { labels: { formatter: val => val.toFixed(0) } }
        }).render();
      }

      // Products QTY Report
      if (document.querySelector("#productsQtyReport")) {
        new ApexCharts(document.querySelector("#productsQtyReport"), {
          series: [
            { name: 'Total Qty', data: qtyValues }
          ],
          chart: { type: 'line', height: 350, toolbar: { show: false } },
          stroke: { curve: 'smooth', width: 3 },
          markers: { size: 4 },
          dataLabels: { enabled: false },
          xaxis: { categories: qtyLabels },
          yaxis: { labels: { formatter: val => val.toFixed(0) } }
        }).render();
      }

      // Active Products Report (pie)
      if (document.querySelector("#activeProductsReport")) {
        new ApexCharts(document.querySelector("#activeProductsReport"), {
          series: [activeProducts, inactiveProducts],
          chart: { type: 'pie', height: 350 },
          labels: ['Active Products', 'Inactive Products'],
          legend: { position: 'bottom' }
        }).render();
      }
    });
  </script>

</body>
</html>
