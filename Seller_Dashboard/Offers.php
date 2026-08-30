<?php
    session_start();

    include "../Connect.php";

    $S_ID = $_SESSION['S_Log'];

    if (! $S_ID) {

        echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

    } else {

        $sql1 = mysqli_query($con, "select * from users where id='$A_ID'");
        $row1 = mysqli_fetch_array($sql1);

        $name  = $row1['name'];
        $email = $row1['email'];
        $image = $row1['image'];

        if (isset($_POST['Submit'])) {

            $S_ID        = $_POST['S_ID'];
            $product_id  = $_POST['product_id'];
            $title       = $_POST['title'];
            $description = $_POST['description'];
            $price       = $_POST['price'];

            $stmt = $con->prepare("INSERT INTO offers (seller_id, product_id, title, description, price) VALUES (?, ?, ?, ?, ?) ");

            $stmt->bind_param("iissd", $S_ID, $product_id, $title, $description, $price);

            if ($stmt->execute()) {


                echo "<script language='JavaScript'>
              alert ('A New Offer Has Been Added Successfully !');
         </script>";

                echo "<script language='JavaScript'>
        document.location='./Offers.php';
           </script>";

            }

        }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Offers - Inventrack</title>
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
        <h1>Offers</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item">Offers</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section">
        <div class="mb-3">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#verticalycentered"
          >
            Add New Offer
          </button>
        </div>

        <div class="modal fade" id="verticalycentered" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Offer Information</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">

                <form method="POST" action="./Offers.php" enctype="multipart/form-data">

                <input type="hidden" name="S_ID" value="<?php echo $S_ID ?>" id="">

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label"
                      >Product</label
                    >
                    <div class="col-sm-8">
                       <select name="product_id" class="form-select" id="">
                        <option value="" selected disabled>Select Product</option>
                       <?php
                           $sql1 = mysqli_query($con, "SELECT * from products WHERE seller_id = '$S_ID' AND active = 1");

                           while ($row1 = mysqli_fetch_array($sql1)) {

                               $product_id   = $row1['id'];
                               $product_name = $row1['name'];

                           ?>

<option value="<?php echo $product_id ?>"><?php echo $product_name ?></option>
    <?php }?>
                       </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label"
                      >Title</label
                    >
                    <div class="col-sm-8">
                      <input type="text" name="title" class="form-control" />
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label"
                      >Description</label
                    >
                    <div class="col-sm-8">
                       <textarea name="description" class="form-control" id=""></textarea>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="price" class="col-sm-4 col-form-label"
                      >Price</label
                    >
                    <div class="col-sm-8">
                      <input type="number" min="0.01" step="0.01" name="price" class="form-control" id="price" required/>
                    </div>
                  </div>



                  <div class="row mb-3">
                    <div class="text-end">
                      <button type="submit" name="Submit" class="btn btn-primary">
                        Submit
                      </button>
                    </div>
                  </div>
                </form>

              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
              <div class="card-body">
                <!-- Table with stripped rows -->
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Title</th>
                      <th scope="col">Product</th>
                      <th scope="col">Price</th>
                      <th scope="col">Created At</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $sql1 = mysqli_query($con, "SELECT * from offers WHERE seller_id = '$S_ID' ORDER BY id DESC");

                      while ($row1 = mysqli_fetch_array($sql1)) {

                          $offer_id    = $row1['id'];
                          $offer_title = $row1['title'];
                          $product_id  = $row1['product_id'];
                          $price       = $row1['price'];
                          $active      = $row1['active'];
                          $created_at  = $row1['created_at'];

                          $sql2 = mysqli_query($con, "SELECT * from products WHERE id = '$product_id'");
                          $row2 = mysqli_fetch_array($sql2);

                          $product_name = $row2['name'];

                      ?>
                    <tr>
                      <th scope="row"><?php echo $offer_id ?></th>
                      <th scope="row"><?php echo $offer_title ?></th>
                      <td><?php echo $product_name ?></td>
                      <td><?php echo $price ?> JODs</td>
                      <th scope="row"><?php echo $created_at ?></th>
                      <td>

              <div class="d-flex flex-column">
              <div class="d-flex mb-2">


                        <?php if ($active == 1) {?>

<a href="./DeleteOrRestoreOffer.php?offer_id=<?php echo $offer_id ?>&isActive=<?php echo 0 ?>" class="btn btn-danger">Delete</a>

<?php } else {?>

  <a href="./DeleteOrRestoreOffer.php?offer_id=<?php echo $offer_id ?>&isActive=<?php echo 1 ?>" class="btn btn-primary">Restore</a>

<?php }?>
                        </div>


              </div>

                      </td>
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
