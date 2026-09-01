<?php


// ✅ DEBUG MODE (DEVELOPMENT ONLY)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Make mysqli throw exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Turn warnings/notices into exceptions so you see them clearly
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    session_start();
    include "../Connect.php";

    // ✅ FIX: session key might not exist if user not logged in
    $B_ID = $_SESSION['B_ID'] ?? null;

} catch (Throwable $e) {
    echo "<h2 style='color:red'>Product.php ERROR</h2>";
    echo "<pre style='background:#111;color:#0f0;padding:15px;border-radius:8px;white-space:pre-wrap;'>";
    echo "Message: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
    exit;
}
?>


<?php
  



    if ($B_ID) {

        $sql211 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row211 = mysqli_fetch_array($sql211);

        $cart_count = $row211['cart_count'];
    }

    $product_id = $_GET['product_id'];

    $sql2 = mysqli_query($con, "select * from products where id='$product_id'");
    $row2 = mysqli_fetch_array($sql2);
    // ✅ Get seller name for this product
$sellerName = "Unknown";
$sellerId   = $row2['seller_id'] ?? null;

if ($sellerId) {
    $sqlSeller = mysqli_query($con, "SELECT name FROM users WHERE id = '$sellerId' LIMIT 1");
    if ($sqlSeller && mysqli_num_rows($sqlSeller) > 0) {
        $rowSeller  = mysqli_fetch_array($sqlSeller);
        $sellerName = $rowSeller['name'];
    }
}


    $productName         = $row2['name'];
    $productTotalRate    = $row2['total_rate'];
    $productImage        = $row2['image'];
    $productDescription  = $row2['description'];
    $productPrice        = $row2['price'];
    $productIsCustomized = $row2['is_customized'];
    $productqty          = $row2['qty'];
    $product_category_id = $row2['category_id'];
    $out_of_stock        = $row2['out_of_stock'];

    $colors = [];
    $sizes  = [];

    if ($productIsCustomized) {

        $sql3 = mysqli_query($con, "select * from product_options where product_id = '$product_id'");

        while ($row3 = mysqli_fetch_array($sql3)) {

            if ($row3['name'] == 'color') {
                $colors[] = [
                    'id'    => $row3['id'],
                    'value' => $row3['value'],
                ];
            } else if ($row3['name'] == 'size') {
                $sizes[] = [
                    'id'    => $row3['id'],
                    'value' => $row3['value'],
                ];
            }
        }
    }

    $sql3 = mysqli_query($con, "select * from categories where id='$product_category_id'");
    $row3 = mysqli_fetch_array($sql3);

    $categoryName = $row3['name'];

    if (isset($_POST['Submit'])) {

    // ✅ must be logged in
    if (!$B_ID) {
        echo "<script>alert('Please login first');</script>";
        echo "<script>document.location='../Login.php';</script>";
        exit;
    }

    $feedback   = trim($_POST['feedback'] ?? '');
    $product_id_post = (int)($_POST['product_id'] ?? 0);

    // ✅ validate
    if ($product_id_post <= 0 || $feedback === '') {
        echo "<script>alert('Please write a review first');</script>";
        echo "<script>document.location='./Product.php?product_id={$product_id_post}&tab=reviews';</script>";
        exit;
    }

    $stmt = $con->prepare("INSERT INTO product_feedbacks (buyer_id, product_id, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $B_ID, $product_id_post, $feedback);
    $stmt->execute();

    echo "<script>alert('Thank you!');</script>";
    echo "<script>document.location='./Product.php?product_id={$product_id_post}&tab=reviews';</script>";
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

        <style>
.product-main-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 35px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
    margin-bottom: 40px;
}

.product-main-image {
    width: 100%;
    height: 500px;
    border-radius: 22px;
    overflow: hidden;
    background: #f5f7f8;
}

.product-main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 20px 25px;
}

.product-category-label {
    display: inline-block;
    background: #0b4f8a;
    color: #ffffff;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    margin-bottom: 18px;
}

.product-title {
    color: #425b5b;
    font-family: "Raleway", sans-serif;
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 14px;
}

.product-seller {
    color: #738087;
    margin-bottom: 20px;
}

.product-seller-link {
    color: #0b4f8a;
    font-weight: 700;
    text-decoration: none;
}

.product-seller-link:hover {
    text-decoration: underline;
}

.product-price-main {
    color: #425b5b;
    font-weight: 800;
    margin-bottom: 20px;
}

.product-description-main {
    color: #6f7f85;
    font-size: 16px;
    line-height: 1.8;
    margin-bottom: 30px;
}

.product-tabs-card {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 22px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    margin-top: 25px;
}

@media (max-width: 991px) {
    .product-main-image {
        height: 400px;
    }

    .product-info {
        padding: 10px 0;
    }

    .product-main-card {
        padding: 22px;
    }
}
</style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
                            <a href="Products.php" class="nav-item nav-link active">Products</a>
                            <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
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
        <span id="cartCount" class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cart_count ?></span>
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
            <h1 class="text-center text-white display-6"><?php echo $productName ?> Detail</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item"><a href="./Products.php" style="color: #fff !important;">Products</a></li>
                <li class="breadcrumb-item active text-white"><?php echo $productName ?> Detail</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Single Product Start -->
        <div class="container-fluid py-5 mt-5">
            <div class="container py-5">
                <div class="row g-4 mb-5">
                   <div class="col-lg-12">
                        <div class="row g-5 align-items-center product-main-card">
                         <div class="col-lg-6">

    <div class="product-main-image">

        <img
            src="../Seller_Dashboard/<?php echo $productImage ?>"
            alt="<?php echo htmlspecialchars($productName); ?>"
        >

    </div>

</div>
                            <div class="col-lg-6 product-info">
                             <span class="product-category-label">
    <?php echo htmlspecialchars($categoryName); ?>
</span>

<h1 class="product-title">
    <?php echo htmlspecialchars($productName); ?>
</h1>

<p class="product-seller">

    Sold by

    <a
        href="./Seller.php?seller_id=<?php echo $sellerId; ?>"
        class="product-seller-link"
    >
        <?php echo htmlspecialchars($sellerName); ?>
    </a>

</p>

<h3 class="product-price-main">
    <?php echo $productPrice ?> JODs
</h3>

<p class="product-description-main">
    <?php echo htmlspecialchars($productDescription); ?>
</p>

                                <?php if ((int)$productqty > 0) { ?>


                                    <?php if ($productIsCustomized) {?>
                                    <div class="mb-3">
                                        <div id="colors-div">
                                            <p>Colors</p>
                                            <select name="color_id" id="color_id" class="border-0 form-select-sm bg-light me-3">
                                                <option value="" selected disabled>Select Color</option>

                                                <?php

                                                        foreach ($colors as $color) {
                                                        ?>
                                                <option value="<?php echo $color['id'] ?>"><?php echo $color['value'] ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div id="sizes-div">
                                            <p>Sizes</p>
                                            <select name="size_id" id="size_id" class="border-0 form-select-sm bg-light me-3">
                                                <option value="" selected disabled>Select Size</option>

                                                <?php

                                                        foreach ($sizes as $size) {
                                                        ?>
                                                <option value="<?php echo $size['id'] ?>"><?php echo $size['value'] ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php }?>

                                    <div class="input-group quantity mb-5" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" id="qty" value="1" max="<?php echo $productqty ?>">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary" id="addToCart"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</button>




                                    <script>

                                        document.getElementById('addToCart').addEventListener('click',function(e) {


                                            const color = document.getElementById('color_id')?.value
                                            const size = document.getElementById('size_id')?.value
                                            const qty = document.getElementById('qty')?.value


                                            const options = JSON.stringify({
                                                color_id : color ?? '',
                                                size_id : size ?? ''
                                            })

                                            const productId =  <?php echo json_encode($product_id) ?>;
                                  
                                            $.ajax({
                                            url: `./AddToCart.php?options=${options}&product_id=${productId}&qty=${qty}`,
                                            type: 'GET',
                                            dataType: 'json',
                                            success: function(data) {
console.log("data ====> ", data);

                                                if(!data.error) {
                                                    document.getElementById('cartCount').innerHTML = data.cart_count
                                                    alert('Product Added To Cart')
                                                } else {
                                                    alert('Something Went wrong')
                                                }
                                            },
                                            error: function(x,y,z) {
                                                console.log(x, y,z);
                                                
                                            }
                                        })
                                        })

                                    </script>
                                                 <?php } else {?>



                                <div class="alert alert-danger fw-bold" role="alert">
  OUT OF STOCK — This item is currently unavailable.
</div>
<button class="btn btn-secondary rounded-pill px-4 py-2 mb-4" disabled>
  <i class="fa fa-shopping-bag me-2"></i> Out of Stock
</button>




                                                 <?php }?>

                            </div>
                            <div class="col-lg-12 product-tabs-card">
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
                                        <p><?php echo $productDescription ?> </p>

                                    </div>
                                    <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">

                                    <?php
                                        $sql33 = mysqli_query($con, "SELECT * from product_feedbacks WHERE product_id = '$product_id' ORDER BY created_at DESC");

                                        while ($row33 = mysqli_fetch_array($sql33)) {

                                            $seller_id  = $row33['id'];
                                            $buyer_id   = $row33['buyer_id'];
                                            $feedback   = $row33['feedback'];
                                            $created_at = $row33['created_at'];

                                            $sql44 = mysqli_query($con, "SELECT * from users WHERE id = '$buyer_id'");
                                            $row44 = mysqli_fetch_array($sql44);

                                            $buyer_name = $row44['name'];

                          


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
                            <form action="./Product.php?product_id=<?php echo $product_id ?>&tab=reviews" method="POST">
                                <h4 class="mb-5 fw-bold">Leave a Reply</h4>
                                <div class="row g-4">

                                <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                

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

                </div>
                <h2 class="fw-bold mb-4">Related Products</h2>

                <div class="vesitable">
                    <div class="owl-carousel vegetable-carousel justify-content-center">


                    <?php
                    $current_product_id = $product_id;
                        $sql55 = mysqli_query(
    $con,
    "SELECT *
     FROM products
     WHERE active = 1
     AND category_id = '$product_category_id'
     AND id != '$current_product_id'
     LIMIT 8"
);

                        while ($row55 = mysqli_fetch_array($sql55)) {

                            $product_id          = $row55['id'];
                            $category_id         = $row55['category_id'];
                            $product_name        = $row55['name'];
                            $product_image       = $row55['image'];
                            $product_price       = $row55['price'];
                            $product_description = $row55['description'];
                            $out_of_stock_rel        = $row55['out_of_stock'];

                            $sql66 = mysqli_query($con, "SELECT * from categories WHERE id = '$category_id'");
                            $row66 = mysqli_fetch_array($sql66);

                            $category_name = $row66['name'];

                        ?>


                        <div class="border border-primary rounded position-relative vesitable-item" id="<?php echo $product_id ?>">
                            <div class="vesitable-img" style="
    height: 180px;
">
                                <img src="../Seller_Dashboard/<?php echo $product_image ?>" class="img-fluid w-100 rounded-top" alt="">
                            </div>
                            <div class="text-white background-sec px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;"><?php echo $category_name ?></div>
                            <span><?php echo $out_of_stock_rel == 1 ? 'Out of stock' : ''?></span>
                            <div class="p-4 pb-0 rounded-bottom">
                                <h4><?php echo $product_name ?></h4>
                                <p><?php echo substr($product_description, 0, 10) ?></p>
                                <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold"><?php echo $product_price ?> JODs</p>
                                    <a href="./Product.php?product_id=<?php echo $product_id ?>" class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary">View Product</a>
                                </div>
                            </div>
                        </div>


<?php 



}?>



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
      // Bootstrap 5 safe way
      if (window.bootstrap && bootstrap.Tab) {
        bootstrap.Tab.getOrCreateInstance(btn).show();
      } else {
        // fallback
        btn.click();
      }

      // optional: scroll to the tabs area (so you SEE the reviews)
      btn.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }
});
</script>


    </body>
    

</html>
