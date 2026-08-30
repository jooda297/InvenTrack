<?php
    session_start();

    include "../Connect.php";

    $S_ID     = $_SESSION['S_Log'];
    $order_id = $_GET['order_id'];

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

    <title>Order Items - Inventrack</title>
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
        <h1>Order Items</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item">Order Items</li>
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
                      <th scope="col">Product</th>
                      <th scope="col">Option</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Price</th>
                      <th scope="col">Total Price</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $sql1 = mysqli_query($con, "SELECT * FROM order_items WHERE order_id = '$order_id' AND seller_id = '$S_ID'");


                      while ($row1 = mysqli_fetch_array($sql1)) {

                        $color_value = '';
                        $size_value  = '';

                          $product_id = $row1['product_id'];
                         $options = [];
if (!empty($row1['option_id'])) {
    $decoded = json_decode($row1['option_id'], true);
    if (is_array($decoded)) {
        $options = $decoded;
    }
}

$color_id = $options['color_id'] ?? '';
$size_id  = $options['size_id'] ?? '';

                          $quantity   = $row1['quantity'];

                          $sql2 = mysqli_query($con, "SELECT * FROM products WHERE id = '$product_id'");
                          $row2 = mysqli_fetch_array($sql2);

                          $product_name  = $row2['name'];
                          $product_price = $row1['product_price'];


                          if ($color_id != '') {

                              $sql66 = mysqli_query($con, "SELECT value from product_options WHERE id = '$color_id'");
                              $row66 = mysqli_fetch_array($sql66);

                              $color_value = $row66['value'];
                          }

                          if ($size_id != '') {

                              $sql77 = mysqli_query($con, "SELECT value from product_options WHERE id = '$size_id'");
                              $row77 = mysqli_fetch_array($sql77);

                              $size_value = $row77['value'];

                          }

                      ?>
                    <tr>
                      <th scope="row"><?php echo $product_name ?></th>
                      <td><?php echo trim($color_value . ' ' . $size_value); ?></td>
                      <td><?php echo $quantity ?></td>
                      <td><?php echo $product_price ?>JODs</td>
                      <td><?php echo($product_price * $quantity) ?> JODs</td>
                    </tr>
<?php
}?>
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
