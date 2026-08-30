<?php
    session_start();

    include "../Connect.php";

    $offer_id = $_GET['offer_id'];
    $B_ID     = $_SESSION['B_ID'];

    if ($B_ID) {

        $sql322 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row322 = mysqli_fetch_array($sql322);

        $cart_count = $row322['cart_count'];
    }

    $sql2 = mysqli_query($con, "select * from offers where id='$offer_id'");
    $row2 = mysqli_fetch_array($sql2);

    $seller_id   = $row2['seller_id'];
    $product_id  = $row2['product_id'];
    $title       = $row2['title'];
    $description = $row2['description'];
    $price       = $row2['price'];

    $sql3 = mysqli_query($con, "select * from users where id='$seller_id'");
    $row3 = mysqli_fetch_array($sql3);

    $seller_name = $row3['name'];

    $sql4 = mysqli_query($con, "select * from products where id='$product_id'");
    $row4 = mysqli_fetch_array($sql4);

    $product_name  = $row4['name'];
    $product_image = $row4['image'];

    if (isset($_POST['Submit'])) {

        $offer_id = $_POST['offer_id'];
        $B_ID     = $_POST['B_ID'];

        $sql2 = mysqli_query($con, "select * from offers where id='$offer_id'");
        $row2 = mysqli_fetch_array($sql2);

        $seller_id  = $row2['seller_id'];
        $product_id = $row2['product_id'];
        $price      = $row2['price'];

        $stmt = $con->prepare("INSERT INTO orders (buyer_id, offer_id, total_price, seller_id) VALUES (?, ?, ?, ?) ");

        $stmt->bind_param("iidi", $B_ID, $offer_id, $price, $seller_id);

        $quantity = 1;

        if ($stmt->execute()) {

            $order_id = $stmt->insert_id;

            $orderItemsStmt = $con->prepare("INSERT INTO order_items (order_id, seller_id, product_id, product_price, quantity) VALUES (?, ?, ?, ?, ?) ");

            $orderItemsStmt->bind_param("iiidi", $order_id, $seller_id, $product_id, $price, $quantity);

            $orderItemsStmt->execute();

            echo "<script language='JavaScript'>
              alert ('Thank you !');
         </script>";

            echo "<script language='JavaScript'>
        document.location='./Orders.php';
           </script>";

        }

    }

?>


<!DOCTYPE html>
<html lang="en">

    <head>
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
        <div class="container-fluid fixed-top"style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
    
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
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="Products.php" class="nav-item nav-link">Products</a>
                            <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
                            <a href="Offers.php" class="nav-item nav-link active">Offers</a>
                            <?php if ($B_ID) {?>
                                <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                                <?php }?>
                            <?php if ($B_ID) {?>
                                <a href="Orders.php" class="nav-item nav-link">Orders</a>
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
            <h1 class="text-center text-white display-6"><?php echo $title ?> Detail</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item"><a href="./Offers.php" style="color: #fff !important;">Offers</a></li>
                <li class="breadcrumb-item active text-white"><?php echo $title ?> Detail</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Single Product Start -->
        <div class="container-fluid py-5 mt-5">
            <div class="container py-5">
                <div class="row g-4 mb-5">
                    <div class="col-lg-8 col-xl-9">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded">
                                        <img src="../Seller_Dashboard/<?php echo $product_image ?>" class="img-fluid rounded" alt="Image" style="width: 100%;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="fw-bold mb-3"><?php echo $product_name ?></h4>
                                <p class="mb-3"><?php echo $description ?></p><?php echo $sellerPhone ?></p>
                                <h5 class="fw-bold mb-3">Price :                                                                                                                                                                                                                                                                                                                                                                                                 <?php echo $price ?> JODs</h5>
                                <div class="d-flex mb-4">

                                    <form action="./Offer.php?offer_id=<?php echo $offer_id ?>" method="POST">
                                        <input type="hidden" name="B_ID" value="<?php echo $B_ID ?>">
                                        <input type="hidden" name="offer_id" value="<?php echo $offer_id ?>">
                                        <button type="submit" name="Submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Purchase Offer</button>
                                    </form>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Description</button>

                                        <!-- <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                            aria-controls="nav-mission" aria-selected="false">Reviews</button> -->
                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <p><?php echo $description ?> </p>

                                    </div>
                                    <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">



                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- Single Product End -->


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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>