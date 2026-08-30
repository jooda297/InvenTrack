<?php
session_start();

include "../Connect.php";

$S_ID = $_SESSION['S_Log'];

if (!$S_ID) {

    echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

} else {

    $sql1 = mysqli_query($con, "SELECT * FROM users WHERE id='$S_ID'");
    $row1 = mysqli_fetch_array($sql1);

    $name = $row1['name'];
    $email = $row1['email'];
    $image = $row1['image'];

    $today = new DateTime();

// Default: no subscription => treat as expired so "Add New Subscription" shows
$endDateTime = (new DateTime())->modify('-1 day');

$sql222 = mysqli_query($con, "SELECT end_date FROM seller_subscriptions WHERE seller_id = '$S_ID' ORDER BY id DESC LIMIT 1");
if ($sql222 && mysqli_num_rows($sql222) > 0) {
    $row2222 = mysqli_fetch_array($sql222);

    if (!empty($row2222['end_date'])) {
        $endDateTime = new DateTime($row2222['end_date']);
    }
}


    if (isset($_POST['Submit'])) {

        $seller_id = $_POST['seller_id'];
        $start_date = $_POST['start_date'];
        $subscription_type = $_POST['subscription_type'];
        $price = 0;

        if ($subscription_type == 1) {

            $end_date = date('Y-m-d', strtotime($start_date . ' +30 days'));
            $subscription_type = "1 Months Contract (65 JOD)";
            $price = 65;

        } else if ($subscription_type == 2) {

            $end_date = date('Y-m-d', strtotime($start_date . ' +180 days'));
            $subscription_type = "6 Months Contract (300 JOD)";
            $price = 300;

        } else if ($subscription_type == 3) {

            $end_date = date('Y-m-d', strtotime($start_date . ' +360 days'));
            $subscription_type = "12 Months COntract (600 JOD)";
            $price = 600;

        }

        $stmt = $con->prepare("INSERT INTO seller_subscriptions (seller_id, subscription_type, start_date, end_date, price) VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("issss", $seller_id, $subscription_type, $start_date, $end_date, $price);

        if ($stmt->execute()) {

            echo "<script language='JavaScript'>
          alert ('A New Subscription Has Been Added Successfully !');
     </script>";

            echo "<script language='JavaScript'>
    document.location='./Subscriptions.php';
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

    <title> Subscriptions - Elite</title>
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
              <span class="d-none d-md-block dropdown-toggle ps-2" style="color: black;"><?php echo $name ?></span> </a
            >

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6 style="color: black;"><?php echo $name ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="./Profile.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Profile</span>
              </a>
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
        <h1 style="color: black;"> Subscriptions</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"> Subscriptions</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section">





<?php if ($endDateTime < $today) {?>

      <div class="mb-3">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#verticalycentered"
          >
            Add New Subscription
          </button>
        </div>
        <?php }?>


        <div class="modal fade" id="verticalycentered" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Subscription Information</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">

                <form method="POST" action="./Subscriptions.php" enctype="multipart/form-data">

<input type="hidden" name="seller_id" value="<?php echo $S_ID ?>">

                  <div class="row mb-3">
                    <label for="startDate" class="col-sm-4 col-form-label"
                      >Contract Start Date</label
                    >
                    <div class="col-sm-8">
                    <input
                          type="date"
                          name="start_date"
                          min="<?php echo date('Y-m-d') ?>"
                          class="form-control"
                          id="startDate"
                          required
                        />
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label"
                      > Contract Type</label
                    >
                    <div class="col-sm-8">
                    <select name="subscription_type" class="form-select" id="subscription_type" required>
                            <option value="1">1 Month Open Contract (65)</option>
                            <option value="2">6 Months Contract (300 JOD)</option>
                            <option value="3">12 Months Contract (600 JOD)</option>
                        </select>
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
                      <th scope="col">Subscription Type</th>
                      <th scope="col">Start Date</th>
                      <th scope="col">End Date</th>
                      <th scope="col">Price</th>
                      <th scope="col">Created At</th>
                    </tr>
                  </thead>
                  <tbody>


                  <?php
$sql1 = mysqli_query($con, "SELECT * from seller_subscriptions WHERE seller_id = '$S_ID' ORDER BY id DESC");

while ($row1 = mysqli_fetch_array($sql1)) {

    $subscription_id = $row1['id'];
    $subscription_type = $row1['subscription_type'];
    $start_date = $row1['start_date'];
    $end_date = $row1['end_date'];
    $price = $row1['price'];
    $active = $row1['active'];
    $created_at = $row1['created_at'];

    ?>
                    <tr>
                      <th scope="row"><?php echo $subscription_id ?></th>
                      <th scope="row"><?php echo $subscription_type ?></th>
                      <td scope="row"><?php echo $start_date ?></td>
                      <td scope="row"><?php echo $end_date ?></td>
                      <td scope="row"><?php echo $price ?> JODs</td>
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
      <div class="copyright" style="color: black;">
        &copy; Copyright <strong><span>Elite</span></strong
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
     document.querySelector('#sidebar-nav .nav-item:nth-child(5) .nav-link').classList.remove('collapsed')
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