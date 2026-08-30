<?php
    session_start();

    include "../Connect.php";

    $S_ID = $_SESSION['S_Log'];

    if (! $S_ID) {

        echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

    } else {

        $sql1 = mysqli_query($con, "select * from users where id='$S_ID'");
        $row1 = mysqli_fetch_array($sql1);

        $name  = $row1['name'];
        $email = $row1['email'];
        $image = $row1['image'];
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Orders - Inventrack</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
  <link href="../assets/img/icon.png" rel="icon" />
    <link href="../assets/img/icon.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="../assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="../assets/vendor/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
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
.status-badge {
  padding: 6px 12px;
  border-radius: 999px;
  font-weight: 600;
  font-size: 13px;
  display: inline-block;
}

.status-pending {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
}

.status-delivering {
  background: #cce5ff;
  color: #004085;
  border: 1px solid #b8daff;
}

.status-delivered {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.status-canceled {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}




    </style>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
          <img src="../assets/img/Logo.png" alt="" />

        </a>
      </div>
      <!-- End Logo -->
      <!-- End Search Bar -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item dropdown pe-3">
            <a
              class="nav-link nav-profile d-flex align-items-center pe-0"
              href="#"
              data-bs-toggle="dropdown"
            >
              <img
                                src="<?php echo $image ?>"
                alt="Profile"
                class="rounded-circle"
              />
              <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $name ?></span> </a
            >

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $name ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="./Logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
          </li>
          <!-- End Profile Nav -->
        </ul>
      </nav>
      <!-- End Icons Navigation -->
    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php require './Aside-Nav/Aside.php'?>
    <!-- End Sidebar-->

    <main id="main" class="main">
      <div class="pagetitle">
        <h1>Orders</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item">Orders</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section">



        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
              <div class="card-body">
                <!-- Table with stripped rows -->
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Customer Name</th>
                      <th scope="col">Status</th>
                      <th scope="col">Total Price</th>
                      <th scope="col">Created At</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $sql1 = mysqli_query($con, "
    SELECT 
        o.id,
        o.buyer_id,
        o.status_id,
        o.total_price,
        o.created_at,
        u.name AS customer_name,
        st.name AS status_name
    FROM orders o
    INNER JOIN order_items oi ON oi.order_id = o.id
    INNER JOIN users u ON u.id = o.buyer_id
    INNER JOIN statuses st ON st.id = o.status_id
    WHERE oi.seller_id = '$S_ID'
    GROUP BY o.id
    ORDER BY o.id DESC
");




                   while ($row1 = mysqli_fetch_array($sql1)) {

    $order_id      = $row1['id'];
    $status_id     = $row1['status_id'];
    $total_price   = $row1['total_price'];
    $created_at    = $row1['created_at'];
    $customer_name = $row1['customer_name'];
    $status_name   = $row1['status_name'];

    // status badge class
    $statusClass = '';
    switch (strtolower(trim($status_name))) {
        case 'pending':
            $statusClass = 'status-pending';
            break;
        case 'delivering':
            $statusClass = 'status-delivering';
            break;
        case 'delivered':
            $statusClass = 'status-delivered';
            break;
        case 'canceled':
        case 'cancelled':
            $statusClass = 'status-canceled';
            break;
        default:
            $statusClass = 'status-pending';
            break;
    }
?>
<tr>
    <th scope="row"><?php echo $order_id ?></th>

    <td><?php echo $customer_name ?></td>

    <td>
        <span class="status-badge <?php echo $statusClass; ?>">
            <?php echo $status_name; ?>
        </span>
    </td>

    <td><?php echo $total_price ?> JODs</td>

    <td><?php echo $created_at ?></td>

    <td>
        <div class="d-flex mb-2">
            <a href="./Order-Items.php?order_id=<?php echo $order_id ?>" class="btn btn-success me-2">Items</a>

            <?php if ($status_id == 1) { ?>
                <a href="./ChangeStatus.php?order_id=<?php echo $order_id ?>&status_id=2" class="btn btn-primary">Start delivery</a>
            <?php } else if ($status_id == 2) { ?>
                <a href="./ChangeStatus.php?order_id=<?php echo $order_id ?>&status_id=3" class="btn btn-primary">Delivered</a>
            <?php } ?>
        </div>
    </td>
</tr>
<?php
}
?>



                  </tbody>
                </table>
                <!-- End Table with stripped rows -->
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>Inventrack</span></strong
        >. All Rights Reserved
      </div>
    </footer>
    <!-- End Footer -->

    <a
      href="#"
      class="back-to-top d-flex align-items-center justify-content-center"
      ><i class="bi bi-arrow-up-short"></i
    ></a>

    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
     document.querySelector('#sidebar-nav .nav-item:nth-child(6) .nav-link').classList.remove('collapsed')
   });
</script>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
  </body>
</html>
