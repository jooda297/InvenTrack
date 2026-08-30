<?php
    session_start();

    include "../Connect.php";

    $seller_id = $_GET['seller_id'];
    $B_ID = $_SESSION['B_ID'] ?? null;


    if ($B_ID) {

        $sql322 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row322 = mysqli_fetch_array($sql322);

        $cart_count = $row322['cart_count'];
    }

    $sql2 = mysqli_query($con, "select * from users where id='$seller_id'");
    $row2 = mysqli_fetch_array($sql2);

    $sellerName        = $row2['name'];
    $sellerEmail       = $row2['email'];
    $sellerPhone       = $row2['phone'];
    $sellerTotalRate   = $row2['total_rate'];
    $sellerImage       = $row2['image'];
    $sellerDescription = $row2['description'];
    $instagram_link    = $row2['instagram_link'];

    if (isset($_POST['Submit'])) {

    // ✅ must be logged in
    if (!$B_ID) {
        echo "<script>alert('Please login first');</script>";
        echo "<script>document.location='../Login.php';</script>";
        exit;
    }

    $feedback   = trim($_POST['feedback'] ?? '');
    $seller_post_id = (int)($_POST['seller_id'] ?? 0);

    if ($seller_post_id <= 0 || $feedback === '') {
        echo "<script>alert('Please write a review first');</script>";
        echo "<script>document.location='./Seller.php?seller_id={$seller_post_id}&tab=reviews';</script>";
        exit;
    }

    $stmt = $con->prepare("INSERT INTO seller_feedbacks (buyer_id, seller_id, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $B_ID, $seller_post_id, $feedback);
    $stmt->execute();

    echo "<script>alert('Thank you!');</script>";
    echo "<script>document.location='./Seller.php?seller_id={$seller_post_id}&tab=reviews';</script>";
    exit;
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
                            <?php if ($B_ID) {?>
                                <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                                <?php }?>
                            <a href="contact.php" class="nav-item nav-link">Contact</a>
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
            <h1 class="text-center text-white display-6"><?php echo $sellerName ?> Detail</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item"><a href="./Sellers.php" style="color: #fff !important;">Sellers</a></li>
                <li class="breadcrumb-item active text-white" style="color: #fff !important;"><?php echo $sellerName ?> Detail</li>
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
                                        <img src="../Seller_Dashboard/<?php echo $sellerImage ?>" class="img-fluid rounded" alt="Image" style="width: 100%;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="fw-bold mb-3"><?php echo $sellerName ?></h4>
                                <p class="mb-3">Phone:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $sellerPhone ?></p>
                                <p class="mb-3">
                                    <a href="<?php echo $instagram_link ?>" target="_blank"><i class="bi bi-instagram"></i></a>
                                    <i class="bi bi-facebook"></i>
                                </p>
                                <h5 class="fw-bold mb-3"></h5>
                                <div class="d-flex mb-4">
                                <?php for ($ii = 1; $ii < $sellerTotalRate; $ii++) {?>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <?php }?>

                                </div>
                                

                            </div>
                            <div class="col-lg-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Description</button>
                                        <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                            aria-controls="nav-mission" aria-selected="false">Reviews</button>
                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <p><?php echo $sellerDescription ?> </p>

                                    </div>
                                    <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">




                                    <?php
                                        $sql33 = mysqli_query($con, "SELECT * from seller_feedbacks WHERE seller_id = '$seller_id'");

                                        while ($row33 = mysqli_fetch_array($sql33)) {

                                            $feedback_id = $row33['id'];
                                            $buyer_id   = $row33['buyer_id'];
                                            $feedback   = $row33['feedback'];
                                            $created_at = $row33['created_at'];

                                            $sql44 = mysqli_query($con, "SELECT * from users WHERE id = '$buyer_id'");
                                            $row44 = mysqli_fetch_array($sql44);

                                            $buyer_name = $row44['name'];

                                            $sql55 = mysqli_query($con, "SELECT rate FROM seller_rate WHERE buyer_id = '$buyer_id' AND seller_id = '$seller_id' LIMIT 1");
$row55 = mysqli_fetch_array($sql55);

// if no rate exists, make it 0 (no stars)
$buyer_seller_rate = $row55['rate'] ?? 0;


                                        ?>

                                        <div class="d-flex">
                                            <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                            <div class="">
                                                <p class="mb-2" style="font-size: 14px;"><?php echo $created_at ?></p>
                                                <div class="d-flex justify-content-between">
                                                    <h5><?php echo $buyer_name ?></h5>
                                                   
                                                </div>
                                                <p><?php echo $feedback ?></p>
                                            </div>
                                        </div>
<?php }?>

                                    </div>

                                </div>
                            </div>
                            <form action="./Seller.php?seller_id=<?php echo $seller_id ?>&tab=reviews" method="POST">
                                <h4 class="mb-5 fw-bold">Leave a Reply</h4>
                                <div class="row g-4">

                                <input type="hidden" name="seller_id" value="<?php echo $seller_id ?>">
                                

                                    <div class="col-lg-12">
                                        <div class="border-bottom rounded my-4">
                                            <textarea name="feedback" id="" class="form-control border-0" cols="30" rows="8" placeholder="Your Review *" spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between py-3 mb-5">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0 me-3"></p>
                                                <div class="d-flex align-items-center" style="font-size: 12px;">

                                                </div>
                                            </div>
                                             <button class="btn border border-secondary text-primary rounded-pill px-4 py-3" type="submit" name="Submit">Post Comment</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xl-3">
                        <div class="row g-4 fruite">



                            <div class="col-lg-12">
                                <h4 class="mb-4">Featured products</h4>

                                <?php
                                    $sql223 = mysqli_query($con, "SELECT * from products WHERE active = 1 AND total_rate >= 3.5 AND seller_id = '$seller_id'");

                                    while ($row223 = mysqli_fetch_array($sql223)) {

                                        $product_id_featured         = $row223['id'];
                                        $product_name_featured       = $row223['name'];
                                        $product_image_featured      = $row223['image'];
                                        $product_total_rate_featured = $row223['total_rate'];
                                        $product_price_featured      = $row223['price'];

                                    ?>

                                <div class="d-flex align-items-center justify-content-start">
                                    <div class="rounded me-4" style="width: 100px; height: 100px;">
                                        <img src="../Seller_Dashboard/<?php echo $product_image_featured ?>" class="img-fluid rounded" alt="Image">
                                    </div>
                                    <div>
                                        <a href="./Product.php?product_id=<?php echo $product_id_featured ?>"><h6 class="mb-2"><?php echo $product_name_featured ?></h6></a>
                                        <div class="d-flex mb-2">
                                        <?php for ($iiii = 1; $iiii < $product_total_rate_featured; $iiii++) {?>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <?php }?>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <h5 class="fw-bold me-2"><?php echo $product_price_featured ?> JODs</h5>
                                            <h5 class="text-danger text-decoration-line-through"></h5>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>

                            </div>

                        </div>
                    </div>

                </div>
                <h1 class="fw-bold mb-0"><?php echo $sellerName ?> products</h1>
                <div class="vesitable">
                    <div class="owl-carousel vegetable-carousel justify-content-center">



                    <?php
                        $sql55 = mysqli_query($con, "SELECT * from products WHERE active = 1 AND seller_id = '$seller_id'");

                        while ($row55 = mysqli_fetch_array($sql55)) {

                            $product_id          = $row55['id'];
                            $category_id         = $row55['category_id'];
                            $product_name        = $row55['name'];
                            $product_image       = $row55['image'];
                            $product_price       = $row55['price'];
                            $product_description = $row55['description'];

                            $sql66 = mysqli_query($con, "SELECT * from categories WHERE id = '$category_id'");
                            $row66 = mysqli_fetch_array($sql66);

                            $category_name = $row66['name'];

                        ?>


                        <div class="border border-primary rounded position-relative vesitable-item" id="<?php echo $product_id ?>">
                            <div class="vesitable-img" style="height: 180px;">
                                <img src="../Seller_Dashboard/<?php echo $product_image ?>" class="img-fluid w-100 rounded-top" alt="">
                            </div>
                            <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;"><?php echo $category_name ?></div>
                            <div class="p-4 pb-0 rounded-bottom">
                                <h4><?php echo $product_name ?></h4>
                                <p><?php echo substr($product_description, 0, 10) ?></p>
                                <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold"><?php echo $product_price ?> JODs</p>
                                    <a href="./Product.php?product_id=<?php echo $product_id ?>" class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary">View Product</a>
                                </div>
                            </div>
                        </div>


<?php }?>


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
<script>
document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  if (params.get("tab") === "reviews") {
    const btn = document.querySelector('button[data-bs-target="#nav-mission"]');
    if (btn) {
      if (window.bootstrap && bootstrap.Tab) {
        bootstrap.Tab.getOrCreateInstance(btn).show();
      } else {
        btn.click();
      }
      btn.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }
});
</script>




    </body>

</html>