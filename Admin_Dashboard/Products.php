<?php
    session_start();

    include "../Connect.php";

    $A_ID = $_SESSION['A_Log'];

    $category_id     = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$sub_category_id = isset($_GET['sub_category_id']) ? (int)$_GET['sub_category_id'] : 0;
$seller_id       = isset($_GET['seller_id']) ? (int)$_GET['seller_id'] : 0;


    if (! $A_ID) {

        echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

    } else {

        $sql1 = mysqli_query($con, "select * from users where id='$A_ID'");
        $row1 = mysqli_fetch_array($sql1);

        $name  = $row1['name'];
        $email = $row1['email'];

        $productSql = '';

        if ($category_id) {

            $sql2 = mysqli_query($con, "select * from categories where id='$category_id'");
            $row2 = mysqli_fetch_array($sql2);

            $selected_name = $row2['name'];

            $productSql = "SELECT * from products WHERE category_id = '$category_id' ORDER BY id DESC";

        } else if ($sub_category_id) {
            $sql2 = mysqli_query($con, "select * from sub_categories where id='$sub_category_id'");
            $row2 = mysqli_fetch_array($sql2);

            $selected_name = $row2['name'];

            $productSql = "SELECT * from products WHERE sub_category_id = '$sub_category_id' ORDER BY id DESC";

        } else if($seller_id) {
          $sql2 = mysqli_query($con, "select * from users where id='$seller_id'");
          $row2 = mysqli_fetch_array($sql2);

          $selected_name = $row2['name'];

          $productSql = "SELECT * from products WHERE seller_id = '$seller_id' ORDER BY id DESC";
        }
        if (!$productSql) {
    // default: show all products
    $selected_name = "All";
    $productSql = "SELECT * FROM products ORDER BY id DESC";
}

    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title><?php echo $selected_name ?> - Inventrack</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="../assets/img/Logo.png" rel="icon" />
    <link href="../assets/img/Logo.png" rel="apple-touch-icon" />

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

    <input type="hidden" value="<?php echo $seller_id?>" name="selleId" id="sellerId">
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
                src="https://www.computerhope.com/jargon/g/guest-user.png"
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
        <h1><?php echo $selected_name ?> products</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><?php echo $selected_name ?> products</li>
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
                    <th scope="col">Image</th>
                    <th scope="col">ID</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $sql1 = mysqli_query($con, $productSql);

                      while ($row1 = mysqli_fetch_array($sql1)) {

                          $product_id    = $row1['id'];
                          $seller_id     = $row1['seller_id'];
                          $product_name  = $row1['name'];
                          $product_image = $row1['image'];
                          $product_price = $row1['price'];
                          $active        = $row1['active'];
                          $created_at    = $row1['created_at'];

                          $sellerSql = mysqli_query($con, "SELECT * FROM users WHERE id = '$seller_id'");
                          $row2      = mysqli_fetch_array($sellerSql);

                          $sellerName = $row2['name'];

                      ?>
                    <tr>
                        <th scope="row"><img src="../Seller_Dashboard/<?php echo $product_image ?>" alt="" width="100px" height="100px"></th>
                      <th scope="row"><?php echo $product_id ?></th>
                      <th scope="row"><?php echo $sellerName ?></th>
                      <td scope="row"><?php echo $product_name ?></td>
                      <td scope="row"><?php echo $product_price ?> JDs</td>
                      <th scope="row"><?php echo $created_at ?></th>

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

      if(document.getElementById('sellerId').value) {

        document.querySelector('#sidebar-nav .nav-item:nth-child(3) .nav-link').classList.remove('collapsed')
      } else {
        document.querySelector('#sidebar-nav .nav-item:nth-child(2) .nav-link').classList.remove('collapsed')
      }
      


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
