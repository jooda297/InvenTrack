<?php
    session_start();

    include "../Connect.php";

    $B_ID = $_SESSION['B_ID'];

    if ($B_ID) {

        $sql211 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row211 = mysqli_fetch_array($sql211);

        $cart_count = $row211['cart_count'];
    }

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <style>
  .status-badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:999px;
    font-weight:700;
    font-size:12px;
    line-height:1;
    border:1px solid transparent;
  }
  .st-pending{ background:#fff3cd; color:#856404; border-color:#ffeeba; }     /* yellow */
  .st-delivery{ background:#cfe2ff; color:#084298; border-color:#004085; }   /* blue */
  .st-delivered{ background:#d1e7dd; color:#0f5132; border-color:#badbcc; }  /* green */
  .st-canceled{ background:#f8d7da; color:#842029; border-color:#f5c2c7; }   /* red */
  .st-default{ background:#e2e3e5; color:#41464b; border-color:#d3d6d8; }    /* gray */
</style>

        <meta charset="utf-8">
        <title>Inventrack</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">
       <link href="../assets/img/icon.png" rel="icon" />
    <link href="../assets/img/icon.png" rel="apple-touch-icon" />

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">

            <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
    <img src="../assets/img/Logo.png" class="logo-inventrack" alt="Inventrack">
</a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="index.php" class="nav-item nav-link ">Home</a>
                            <a href="Products.php" class="nav-item nav-link">Products</a>
                            <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
                            <?php if ($B_ID) {?>
                                <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                                <?php }?>
                            <?php if ($B_ID) {?>
                                <a href="Orders.php" class="nav-item nav-link active">Orders</a>
                            <?php }?>
<?php if (! $B_ID) {?>
                            <a href="../Login.php" class="nav-item nav-link">Login</a>
                            <?php } else {?>
                                <a href="./Logout.php" class="nav-item nav-link">Logout</a>
                            <?php }?>
                        </div>
                        <?php if ($B_ID) {?>

<div class="d-flex m-3 me-0">
    <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
    <a href="./Cart.php" class="position-relative me-4 my-auto">
        <i class="fa fa-shopping-bag fa-2x"></i>
        <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cart_count ?></span>
    </a>
    <a href="./Profile.php" class="my-auto">
        <i class="fas fa-user fa-2x"></i>
    </a>
</div>

<?php }?>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <form class="w-75 mx-auto d-flex" action="./Products.php" method="POST">
                                <input type="search" class="form-control p-3" name="product_name" placeholder="product name" aria-describedby="search-icon-1">
                                <button type="submit" id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Orders</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item active text-white" style="color: #fff !important;">Orders</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Cart Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Items</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody>

                        <?php
                            $sql11 = mysqli_query($con, "SELECT * from orders WHERE buyer_id = '$B_ID'");

                            while ($row11 = mysqli_fetch_array($sql11)) {

                                $order_id    = $row11['id'];
                                $status_id   = $row11['status_id'];
                                $total_price = $row11['total_price'];
                                $created_at  = $row11['created_at'];

                                $sql22 = mysqli_query($con, "SELECT name AS status_name from statuses WHERE id = '$status_id'");
                                $row22 = mysqli_fetch_array($sql22);

                                $status_name = $row22['status_name'];

                            ?>

                            <tr>

                                <td>
                                    <p class="mb-0 mt-4"><?php echo $order_id ?></p>
                                </td>

                                <td>
                                    <p class="mb-0 mt-4"><?php echo $total_price ?> JODs</p>
                                </td>

                                <td>
  <?php
    $badgeClass = 'secondary';

    if ($status_id == 1) {
        $badgeClass = 'warning';   // Pending
    } elseif ($status_id == 2) {
        $badgeClass = 'info';      // In Delivery
    } elseif ($status_id == 3) {
        $badgeClass = 'success';   // Delivered
    } elseif ($status_id == 4) {
        $badgeClass = 'danger';    // Canceled
    }
  ?>

  <span class="badge bg-<?php echo $badgeClass ?> mt-4">
    <?php echo $status_name ?>
  </span>
</td>


                                <td>
                                <button onclick="onClick(event)" data-bs-toggle="modal" data-bs-target="#verticalycentered" class="btn btn-success" id="btn-<?php echo $order_id ?>">View Items</button>
                                </td>

                                <td>
                                    <p class="mb-0 mt-4"><?php echo $created_at ?></p>
                                </td>
                                
                                <td>


                                <?php if ($status_id == 1) {?>

                                    <a href="./CancelOrder.php?order_id=<?php echo $order_id ?>" class="btn btn-md rounded-circle bg-light border mt-4 delete-btn" >
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                 <?php } else if ($status_id == 3) { ?>
    <a href="./Rate-Form.php?order_id=<?php echo $order_id; ?>" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">
        Rate
    </a>
<?php } ?>





                                </td>

                            </tr>


<?php
    }
?>

                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="verticalycentered" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Items</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">



              <div class="col-sm-12 col-md-12 col-lg-12">

                  <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Seller Name</th>
                              <th scope="col">Item Name</th>
                              <th scope="col">Price</th>
                              <th scope="col">Options</th>
                              <th scope="col">QTY</th>
                              <th scope="col">Total</th>
                            </tr>
                          </thead>
                          <tbody id="modaltbody">



                          </tbody>
                      </table>
              </div>




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

            </div>
        </div>
        <!-- Cart Page End -->


        <!-- Footer Start -->
            <?php require './Footer.php'?>
        <!-- Footer End -->




        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

   <script>
  const onClick = async (e) => {
    const orderId = e.target.id.replace('btn-', '');
    $('#modaltbody').empty();

    try {
      const res = await fetch(`./Get_order_items.php?order_id=${orderId}`);

      // إذا السيرفر رجّع Error/Warning أو 404
      const text = await res.text();

      // جرّبي تحوليها JSON
      const data = JSON.parse(text);

      data.forEach(item => {
        const itemHtml = `
          <tr>
            <td><p class="mb-0 mt-4">${item.seller_name ?? ''}</p></td>
            <td><p class="mb-0 mt-4">${item.product_name ?? ''}</p></td>
            <td><p class="mb-0 mt-4">${item.price ?? 0} JODs</p></td>
            <td><p class="mb-0 mt-4">${(item.color_value ?? '')} ${(item.size_value ?? '')}</p></td>
            <td><p class="mb-0 mt-4">${item.qty ?? 0}</p></td>
            <td><p class="mb-0 mt-4">${(item.price ?? 0) * (item.qty ?? 0)} JODs</p></td>
          </tr>
        `;
        $('#modaltbody').append(itemHtml);
      });

      if (!data.length) {
        $('#modaltbody').append(`<tr><td colspan="6" class="text-center">No items found</td></tr>`);
      }

    } catch (err) {
      console.log("Get items error:", err);
      $('#modaltbody').append(`<tr><td colspan="6" class="text-center text-danger">Error loading items (check console)</td></tr>`);
    }
  };
</script>





    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    </body>

</html>