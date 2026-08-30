<?php
session_start();

include "../Connect.php";

$A_ID = $_SESSION['A_Log'];
$sub_category_id = $_GET['sub_category_id'];

if (!$A_ID) {

    echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

} else {

    $sql1 = mysqli_query($con, "select * from users where id='$A_ID'");
    $row1 = mysqli_fetch_array($sql1);

    $name = $row1['name'];
    $email = $row1['email'];

    $sql2 = mysqli_query($con, "select * from sub_categories where id='$sub_category_id'");
    $row2 = mysqli_fetch_array($sql2);

    $subCategoryName = $row2['name'];
    $category_id = $row2['category_id'];

    if (isset($_POST['Submit'])) {

        $sub_category_id = $_POST['sub_category_id'];
        $category_id = $_POST['category_id'];
        $sub_category_name = $_POST['name'];
        $image = $_FILES["file"]["name"];

        if ($image) {

            $image = 'Categories_Images/' . $image;

            $stmt = $con->prepare("UPDATE sub_categories SET name = ?, image = ? WHERE id = ? ");

            $stmt->bind_param("ssi", $sub_category_name, $image, $sub_category_id);

            if ($stmt->execute()) {

                move_uploaded_file($_FILES["file"]["tmp_name"], "./Categories_Images/" . $_FILES["file"]["name"]);

                echo "<script language='JavaScript'>
                alert ('Category Has Been Updated Successfully !');
           </script>";

                echo "<script language='JavaScript'>
          document.location='./Sub_Categories.php?category_id={$category_id}';
             </script>";

            }
        } else {

            $stmt = $con->prepare("UPDATE sub_categories SET name = ? WHERE id = ? ");

            $stmt->bind_param("si", $sub_category_name, $sub_category_id);

            if ($stmt->execute()) {

                echo "<script language='JavaScript'>
              alert ('Category Has Been Updated Successfully !');
         </script>";

                echo "<script language='JavaScript'>
        document.location='./Sub_Categories.php?category_id={$category_id}';
           </script>";

            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Category - Inventrack</title>
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
        <h1>Category</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item">Category</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section">
        <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"></h5>

                <!-- Horizontal Form -->
                <form method="POST" action="./Edit_Sub_Category.php?sub_category_id=<?php echo $sub_category_id ?>" enctype="multipart/form-data">

                <input type="hidden" name="sub_category_id" value="<?php echo $sub_category_id ?>">
                <input type="hidden" name="category_id" value="<?php echo $category_id ?>">

                  <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"
                      >Category Name</label
                    >
                    <div class="col-sm-10">
                      <input type="text" name="name" value="<?php echo $subCategoryName ?>" class="form-control" id="inputText" required/>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"
                      >Category Image</label
                    >
                    <div class="col-sm-10">
                      <input type="file" name="file" class="form-control" id="inputText"/>
                    </div>
                  </div>

                  <div class="text-end">
                    <button type="submit" name="Submit" class="btn btn-primary">
                      Submit
                    </button>
                    <button type="reset" class="btn btn-secondary">
                      Reset
                    </button>
                  </div>
                </form>
                <!-- End Horizontal Form -->
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
     document.querySelector('#sidebar-nav .nav-item:nth-child(2) .nav-link').classList.remove('collapsed')
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
