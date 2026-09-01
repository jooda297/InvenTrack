    <?php
    session_start();

    include "../Connect.php";

    $B_ID = $_SESSION['B_ID'];

    if ($B_ID) {

        $sql1 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row1 = mysqli_fetch_array($sql1);

        $cart_count = $row1['cart_count'];
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
            

            .love-icon {
                position: absolute;
                top: 10px;
                right: 10px;
                color: red;
                z-index: 10;
                font-size: 20px;
            }

            .not-fav {

                position: absolute;
                top: 10px;
                right: 10px;
                color: white;
                z-index: 10;
                font-size: 20px;
            }
    </style>
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
    
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
        <span
    id="cartCount"
    class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
    style="top: -5px; left: 15px; height: 20px; min-width: 20px;"
>
    <?php echo $cart_count ?>
</span>
    <a href="./Profile.php" class="my-auto">
        <i class="fas fa-user fa-2x"></i>
    </a>
</div>

<?php }?>
                    </div>
                </nav>
            </div>
        </div>


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


        <!-- Hero Start -->
        <div class="container-fluid py-2 mb-3 hero-header" style="background: linear-gradient(135deg, #c9daeeff 0%, #3a7eccff 100%);">
  <div class="container py-3">
    <div class="row g-5 align-items-center">
      <div class="col-12">
       <div style="text-align:center; margin-top: 1.5rem; margin-bottom: .5rem;">
    <h2 style="
        font-family: 'Raleway', sans-serif;
        font-weight: 800;
        font-size: 2.6rem;
        color: #134681;
        letter-spacing: .5px;
        margin: 0;
    ">
        Our Sellers
    </h2>
</div>


       <div class="seller-carousel">
      
    <?php
        $sql1 = mysqli_query($con, "SELECT * from users WHERE active = 1 and user_type_id = 2");

        $sellers = [];
        while ($row1 = mysqli_fetch_array($sql1)) {
            $sellers[] = $row1;
        }

        // we need at least 3 items for the full effect; if fewer, just show what we have
        $count = count($sellers);
        foreach ($sellers as $index => $seller) {

            $seller_id_carousel   = $seller['id'];
            $sellerName_coursel   = $seller['name'];
            $sellerImage_carousel = $seller['image'];
            $sellerdescription_carousel = $seller['description'];

            // initial position classes: left / main / right / normal
            if ($index === 0) {
                $extraClass = ' seller-carousel__item--left';
            } elseif ($index === 1) {
                $extraClass = ' seller-carousel__item--main';
            } elseif ($index === 2) {
                $extraClass = ' seller-carousel__item--right';
            } else {
                $extraClass = '';
            }
    ?>
    <div class="seller-carousel__item<?php echo $extraClass; ?>" onclick="goToSeller(<?php echo $seller_id_carousel; ?>)">
        <img
            src="../Seller_Dashboard/<?php echo $sellerImage_carousel; ?>"
            alt="<?php echo htmlspecialchars($sellerName_coursel); ?>"
        >
        <div class="seller-carousel__text">
            <h3><?php echo htmlspecialchars($sellerName_coursel); ?></h3>
            <p><?php echo htmlspecialchars($sellerdescription_carousel); ?></p>
        </div>
    </div>
    <?php } ?>

    <div class="seller-carousel__btns">
        <button class="seller-carousel__btn" id="sellerLeftBtn">
            <!-- left arrow svg -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M15 4l-8 8 8 8"/>
            </svg>
        </button>
        <button class="seller-carousel__btn" id="sellerRightBtn">
            <!-- right arrow svg -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M9 4l8 8-8 8"/>
            </svg>
        </button>
    </div>
</div>

<script>
    function goToSeller(id) {
        window.location = './Seller.php?seller_id=' + id;
    }
</script>

      </div>
    </div>
  </div>
</div>
        <!-- Hero End -->
        <script>
        const onClick = (id) => {

            console.log(id);

            document.location = `./Seller.php?seller_id=${id}`
        }
    </script>

        <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">

                    <div class="col-md-6 col-lg-4">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle background-sec mb-5 mx-auto">
                                <i class="fas fa-user-shield fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Security Payment</h5>
                                <p class="mb-0">100% security payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle background-sec mb-5 mx-auto">
                                <i class="fas fa-exchange-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>30 Day Return</h5>
                                <p class="mb-0">30 day money guarantee</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle background-sec mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>24/7 Support</h5>
                                <p class="mb-0">Support every time fast</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs Section End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <div class="tab-class text-center">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start">
                            <h1>some of our Products</h1>
                        </div>
                        <div class="col-lg-8 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">All Products</span>
                                    </a>
                                </li>
                            <?php
                                $sql1 = mysqli_query($con, "SELECT * from categories WHERE active = 1");

                                while ($row1 = mysqli_fetch_array($sql1)) {

                                    $category_id   = $row1['id'];
                                    $category_name = $row1['name'];

                                ?>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-<?php echo $category_id + 1 ?>">
                                        <span class="text-dark" style="width: 130px;"><?php echo $category_name ?></span>
                                    </a>
                                </li>
<?php }?>

                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">

                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                   <?php
                                        $sql1 = mysqli_query($con, "SELECT * from products WHERE active = 1 AND total_rate >= 3.5");

                                        while ($row1 = mysqli_fetch_array($sql1)) {

                                            $product_id          = $row1['id'];
                                            $seller_id           = $row1['seller_id'];
                                            $category_id         = $row1['category_id'];
                                            $product_name        = substr($row1['name'], 0, 10) . '...';
                                            $product_description = $row1['description'];
                                            $product_image       = $row1['image'];
                                            $product_price       = $row1['price'];
                                            $active              = $row1['active'];
                                            $created_at          = $row1['created_at'];
                                            $out_of_stock        = $row1['out_of_stock'];

                                           $sellerSql = mysqli_query($con, "SELECT name FROM users WHERE id = '$seller_id' LIMIT 1");
$sellerRow = mysqli_fetch_assoc($sellerSql);
$sellerName = $sellerRow['name'] ?? 'Unknown Seller';

$categorySql = mysqli_query($con, "SELECT name FROM categories WHERE id = '$category_id' LIMIT 1");
$categoryRow = mysqli_fetch_assoc($categorySql);
$categoryName = $categoryRow['name'] ?? 'Unknown Category';

$isFavorite = 0; // default
if (!empty($B_ID)) {
    $favSql = mysqli_query(
        $con,
        "SELECT active FROM favorites WHERE product_id = '$product_id' AND buyer_id = '$B_ID' LIMIT 1"
    );
    $favRow = mysqli_fetch_assoc($favSql);

    // if no row exists, $favRow is null => keep default 0
    if ($favRow) {
        $isFavorite = (int)$favRow['active'];
    }
}


                                        ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
    <div
        class="rounded position-relative fruite-item position-relative product-clickable"
        onclick="window.location.href='./Product.php?product_id=<?php echo $product_id ?>'"
        style="cursor: pointer;"
    >
                                                <?php if ($B_ID) {?>
                                            <i
    id="icon-<?php echo $product_id ?>"
    onclick="event.stopPropagation(); addToFav(<?php echo $B_ID ?>,<?php echo $product_id ?>)"
    class="<?php echo $isFavorite ? 'fa fa-heart love-icon' : 'fa fa-heart not-fav' ?>">
</i>
                                            <?php }?>

                                                <div class="fruite-img">
                                                    <img src="../Seller_Dashboard/<?php echo $product_image ?>" class="img-fluid w-100 rounded-top" alt="" style="height: 220px;">
                                                </div>
                                                <div class="text-white background-sec px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $categoryName ?></div>
                                                <div class="p-4 rounded-bottom shadow-sm">
                                                    <h4><?php echo $product_name ?></h4>
                                                    <p><?php echo substr($product_description, 0, 10) . '.....' ?></p>
                                                   
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold mb-0"><?php echo $product_price ?> JODs</p>
                                                       
                                                                    <?php if ($out_of_stock == 0) {?>
                                                                    <button
                                                                    type="button" 
                                                                    onclick="event.stopPropagation(); addProductToCart(<?php echo $product_id ?>);"
                                                                    class="btn border border-secondary rounded-pill px-3 text-primary">
                                                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart </button>
                                                        <?php } else {?>
                                                            <p class="text-dark fs-5 fw-bold mb-0">Out Of Stock</p>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                      <?php }?>

                                    </div>
                                </div>
                            </div>
                        </div>




                        <?php
                            $categoryTabSql = mysqli_query($con, "SELECT * from categories WHERE active = 1");

                            while ($categoryTabRow = mysqli_fetch_array($categoryTabSql)) {

                                $category_id_tab   = $categoryTabRow['id'];
                                $category_name_tab = $categoryTabRow['name'];

                            ?>

                        <div id="tab-<?php echo $category_id_tab + 1 ?>" class="tab-pane fade show p-0">

                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                    <?php
                                        $productTabSql = mysqli_query($con, "SELECT * from products WHERE active = 1 AND category_id = '$category_id_tab' AND total_rate >= 3.5");

                                            while ($productTabRow = mysqli_fetch_array($productTabSql)) {

                                                $product_id              = $productTabRow['id'];
                                                $seller_id_tab           = $productTabRow['seller_id'];
                                                $product_name_tab        = substr($productTabRow['name'], 0, 10) . '...';
                                                $product_description_tab = $productTabRow['description'];
                                                $product_image_tab       = $productTabRow['image'];
                                                $product_price_tab       = $productTabRow['price'];
                                                $active_tab              = $productTabRow['active'];
                                                $created_at_tab          = $productTabRow['created_at'];
                                                          $out_of_stock_tab        = $productTabRow['out_of_stock'];

                                                $sellerSqlTab = mysqli_query($con, "SELECT * FROM users WHERE id = '$seller_id_tab'");

                                                $sellerRowTab = mysqli_fetch_array($sellerSqlTab);

                                                $sellerNameTab = $sellerRowTab['name'];

                                            ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div
    class="rounded position-relative fruite-item"
    onclick="window.location.href='./Product.php?product_id=<?php echo $product_id ?>'"
    style="cursor: pointer;"
>

                                                <div class="fruite-img">
                                                    <img src="../Seller_Dashboard/<?php echo $product_image_tab ?>" class="img-fluid w-100 rounded-top" alt="" style="height: 220px;">
                                                </div>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $category_name_tab ?></div>
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4><?php echo $product_name_tab ?></h4>
                                                    <p><?php echo substr($product_description_tab, 0, 10) . '.....' ?></p>
                                                
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold mb-0"><?php echo $product_price_tab ?> JODs</p>
                                                      
                                                               <?php if ($out_of_stock_tab == 0) {?>
                                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>

                                                        <?php } else {
                                                                    ?>
                                 <p class="text-dark fs-5 fw-bold mb-0">Out Of Stock</p>
                                                        <?php }
                                                                ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
<?php }?>

                                    </div>
                                </div>
                            </div>
                        </div>
<?php }?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fruits Shop End-->





        


        <!-- Banner Section Start-->
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <!-- Hero text (left side) -->
<h1 class="text-white display-4 fw-bold">
  Online shop<br>for Inventrack
</h1>
<h2 class="text-white-50 fw-semibold" style="font-size: 1.9rem;">
  Manage home-business orders in one place
</h2>
<p class="text-white-50 mt-3" style="max-width: 430px;">
  Inventrack lets small brands track inventory, manage customers,
  and sell their products through one smart system.
</p>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="./img/banner1.png" class="img-fluid w-100 rounded" alt="">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Section End -->




        <!-- Fact Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light p-5 rounded">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users secondry-text"></i>
                                <h4>satisfied customers</h4>
                                <h1>1963</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users secondry-text"></i>
                                <h4>quality of service</h4>
                                <h1>99%</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users secondry-text"></i>
                                <h4>quality certificates</h4>
                                <h1>33</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users secondry-text"></i>
                                <h4>Available Products</h4>
                                <h1>789</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fact Start -->





        <!-- Footer Start -->
         <?php require './Footer.php'; ?>
        <!-- Footer End -->




        <!-- Back to Top -->
      <a href="#"
   class="btn rounded-circle back-to-top"
   style="background-color:#134681 ;
          color:#ffffff !important;">
    <i class="fa fa-arrow-up" style="color:#ffffff !important;"></i>
</a>



    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <script>
    const addToFav = (customerId, productId) => {
        fetch(`./AddToFavorite.php?buyer_id=${customerId}&product_id=${productId}`)
            .then((response) => response.json())
            .then(data => {
                if (data.error) {
                    if (data.message) {
                        alert(data.message);
                    }
                    return;
                }

                const icon = document.getElementById(`icon-${productId}`);
                if (!icon) return;

                // toggle classes based on action
                if (data.action === 'added') {
                    icon.classList.remove('not-fav');
                    icon.classList.add('love-icon');
                } else if (data.action === 'removed') {
                    icon.classList.remove('love-icon');
                    icon.classList.add('not-fav');
                }

                // If we're on Favorites.php, also remove the card from the page when unfavorited
                if (data.action === 'removed') {
                    const card = document.getElementById(`fav-card-${productId}`);
                    if (card) {
                        card.remove();
                    }
                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong, please try again.');
            });
    };
</script>


    <!-- Template Javascript -->
     <script>
function addProductToCart(productId) {

    <?php if (!$B_ID) { ?>

        alert('Please login first');
        window.location.href = '../Login.php';
        return;

    <?php } ?>

    const options = encodeURIComponent(JSON.stringify({}));

    fetch(`./AddToCart.php?product_id=${productId}&qty=1&options=${options}`)
        .then(response => response.json())
        .then(data => {

            if (!data.error) {

                alert('Product added to cart');

                const cartCount = document.getElementById('cartCount');

                if (cartCount && data.cart_count !== undefined) {
                    cartCount.innerHTML = data.cart_count;
                }

            } else {

                alert(data.message || 'Could not add product to cart');

            }

        })
        .catch(error => {
            console.error(error);
            alert('Something went wrong');
        });
}
</script>

    <script src="js/main.js"></script>
   <script>
document.addEventListener('DOMContentLoaded', function () {
    const items   = Array.from(document.querySelectorAll('.seller-carousel__item'));
    const leftBtn  = document.querySelector('#sellerLeftBtn');
    const rightBtn = document.querySelector('#sellerRightBtn');

    if (items.length === 0) return;

    // find which item is main now (from PHP classes)
    let current = items.findIndex(item =>
        item.classList.contains('seller-carousel__item--main')
    );
    if (current === -1) current = 0;   // fallback

    function applyClasses() {
        // clear all roles
        items.forEach(el => el.className = 'seller-carousel__item');

        const leftIndex  = (current - 1 + items.length) % items.length;
        const rightIndex = (current + 1) % items.length;

        items[current].classList.add('seller-carousel__item--main');
        items[leftIndex].classList.add('seller-carousel__item--left');
        items[rightIndex].classList.add('seller-carousel__item--right');
    }

    // initial layout
    applyClasses();

    rightBtn.addEventListener('click', function () {
        if (items.length < 2) return;
        current = (current + 1) % items.length;
        applyClasses();
    });

    leftBtn.addEventListener('click', function () {
        if (items.length < 2) return;
        current = (current - 1 + items.length) % items.length;
        applyClasses();
    });
});
</script>


    </body>

</html>