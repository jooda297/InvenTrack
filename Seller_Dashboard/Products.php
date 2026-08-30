<?php
session_start();

include "../Connect.php";

$S_ID = $_SESSION['S_Log'];

if (!$S_ID) {

    echo '<script language="JavaScript">
     document.location="../Login.php";
    </script>';

} else {

    $sql1 = mysqli_query($con, "select * from users where id='$S_ID'");
    $row1 = mysqli_fetch_array($sql1);

    $name = $row1['name'];
    $email = $row1['email'];
    $image = $row1['image'];
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host   = $_SERVER['HTTP_HOST'];
$baseURL = $scheme . "://" . $host . "/Inventrack";


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Products - Inventrack</title>
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
        <h1>Products</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item">Products</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
      <section class="section">
        <div class="mb-3">
          <a
          href="./Add-Product.php"
            class="btn btn-primary"
          >
            Add New Product
</a>
        </div>


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
                      <th scope="col">Name</th>
                      <th scope="col">Price</th>
                      <th scope="col">QTY</th>
                      <th scope="col">Created At</th>
                      <th scope="col">QR</th>
                      <th scope="col">Actions</th>

                    </tr>
                  </thead>
                  <tbody>
                  <?php
$sql1 = mysqli_query($con, "SELECT * from products WHERE seller_id = '$S_ID' AND qty > 0 ORDER BY id DESC");

while ($row1 = mysqli_fetch_array($sql1)) {

    $product_id = $row1['id'];
    $qrLink   = $baseURL . "/qr.php?pid=" . $product_id;
$qrImage  = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($qrLink);
    $product_name = $row1['name'];
    $product_image = $row1['image'];
    $product_price = $row1['price'];
    $product_qty = $row1['qty'];
    $active = $row1['active'];
    $created_at = $row1['created_at'];

    ?>
                    <tr>
                        <th scope="row"><img src="<?php echo $product_image ?>" alt="" width="50px" height="50px"></th>
                      <th scope="row" class="product-id-cell" data-product-id="<?php echo $product_id; ?>">
  <?php echo $product_id; ?>
</th>
                      <td><?php echo $product_name ?></td>
                      <td><?php echo $product_price ?> JODs</td>
                      <td class="qty-cell" data-product-id="<?php echo $product_id; ?>">
  <?php echo $product_qty ?>
</td>

                      <th scope="row"><?php echo $created_at ?></th>

<td class="text-center">

  <!-- QR thumbnail opens modal -->
  <a href="#" data-bs-toggle="modal" data-bs-target="#qrModal_<?php echo $product_id; ?>">
    <img src="<?php echo $qrImage; ?>" alt="QR"
         style="width:70px;height:70px;border:1px solid #ddd;border-radius:6px;cursor:pointer;">
  </a>

  <div class="mt-2 d-flex justify-content-center gap-2">
    <!-- View (opens qr.php link) -->
    <a class="btn btn-sm btn-outline-primary" target="_blank" href="<?php echo $qrLink; ?>">
      View
    </a>

    <!-- Print -->
    <button type="button" class="btn btn-sm btn-outline-dark"
            onclick="printQR('<?php echo $qrImage; ?>','<?php echo htmlspecialchars($product_name, ENT_QUOTES); ?>', '<?php echo $product_id; ?>')">
      Print
    </button>
  </div>

  <!-- Modal for BIG QR -->
  <div class="modal fade" id="qrModal_<?php echo $product_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">QR - <?php echo htmlspecialchars($product_name); ?> (ID: <?php echo $product_id; ?>)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body text-center">
          <img src="<?php echo $qrImage; ?>" alt="QR" style="width:260px;height:260px;">
          <div class="mt-3 small text-muted">
            Opens: <?php echo htmlspecialchars($qrLink); ?>
          </div>
        </div>

        <div class="modal-footer">
          <a class="btn btn-outline-primary" target="_blank" href="<?php echo $qrLink; ?>">Open Link</a>
          <button type="button" class="btn btn-dark"
                  onclick="printQR('<?php echo $qrImage; ?>','<?php echo htmlspecialchars($product_name, ENT_QUOTES); ?>', '<?php echo $product_id; ?>')">
            Print
          </button>
        </div>

      </div>
    </div>
  </div>

</td>



<td>


              <div class="d-flex flex-column">
              <div class="d-flex mb-2">
                        <a href="./Edit-Product.php?product_id=<?php echo $product_id ?>" class="btn btn-success me-2"
                          ><i class="bi bi-pencil"></i></a
                        >

                   <a href="./DeleteProduct.php?product_id=<?php echo $product_id; ?>"
   class="btn btn-danger"
   onclick="return confirm('Are you sure you want to permanently delete this product?');">
   <i class="bi bi-trash"></i>
</a>


                        </div>


                        <div class="d-flex mb-2">
                        <!-- <a href="./Product-Images.php?product_id=<?php echo $product_id ?>" class="btn btn-success me-2"
                          ><i class="bi bi-file-earmark-image"></i></a
                        > -->

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
     document.querySelector('#sidebar-nav .nav-item:nth-child(3) .nav-link').classList.remove('collapsed')
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

    <script src="http://<?= $_SERVER['HTTP_HOST'] ?>:3000/socket.io/socket.io.js"></script>




   <script>
  const sellerId = <?php echo json_encode((int)$S_ID); ?>;

  // ✅ correct string
  const socket = io(`${location.protocol}//${location.hostname}:3000`);

  socket.on("connect", () => {
    console.log("Socket connected:", socket.id);
  });

  socket.on("product-updated", (data) => {
    const { product_id, new_quantity, seller_id } = data;

    if (String(sellerId) !== String(seller_id)) return;

    // ✅ correct selector string
    const qtyCell = document.querySelector(`.qty-cell[data-product-id="${product_id}"]`);

    if (qtyCell) {
      qtyCell.textContent = new_quantity;
    } else {
      location.reload();
    }
  });
</script>


<script>
  function printQR(qrImageUrl, productName, productId){
    const w = window.open('', '_blank', 'width=600,height=700');
    w.document.write(`
      <html>
        <head>
          <title>Print QR</title>
          <style>
            body{font-family:Arial; text-align:center; padding:30px;}
            img{width:320px; height:320px;}
            .title{font-size:20px; font-weight:bold; margin-bottom:10px;}
            .sub{color:#444; margin-top:8px;}
          </style>
        </head>
        <body>
          <div class="title">${productName} (ID: ${productId})</div>
          <img src="${qrImageUrl}" />
          <div class="sub">Inventrack Product QR</div>
          <script>
            window.onafterprint = function(){ window.close(); };

window.onload = function(){
  setTimeout(function(){
    window.focus();
    window.print();
  }, 300);
};

          <\/script>
        </body>
      </html>
    `);
    w.document.close();
  }
</script>

  </body>
</html>