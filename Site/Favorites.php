<?php
session_start();
include "../Connect.php";

$B_ID = isset($_SESSION['B_ID']) ? $_SESSION['B_ID'] : null;

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
            cursor: pointer;
        }
        .not-fav {
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            z-index: 10;
            font-size: 20px;
            cursor: pointer;
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
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="Products.php" class="nav-item nav-link">Products</a>
                        <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
                        <?php if ($B_ID) { ?>
                            <a href="Favorites.php" class="nav-item nav-link active">Favorites</a>
                        <?php } ?>
                        <?php if ($B_ID) { ?>
                            <a href="Orders.php" class="nav-item nav-link">Orders</a>
                        <?php } ?>
                        <?php if (!$B_ID) { ?>
                            <a href="../Login.php" class="nav-item nav-link">Login</a>
                        <?php } else { ?>
                            <a href="./Logout.php" class="nav-item nav-link">Logout</a>
                        <?php } ?>
                    </div>
                    <?php if ($B_ID) { ?>
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
                    <?php } ?>
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
        <h1 class="text-center text-white display-6">Favorites</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
            <li class="breadcrumb-item active" style="color: #fff !important;">Favorites</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Favorites Start -->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Favorites</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4 justify-content-center" id="sellers_div">

                        <?php

                        if ($B_ID) {
                            $favSql = mysqli_query($con, "SELECT * FROM favorites
WHERE buyer_id = '$B_ID' AND active = 1");
                            while ($favRow = mysqli_fetch_array($favSql)) {
                                $product_id = $favRow['product_id'];

                                $productsSql = mysqli_query($con, "SELECT * FROM products WHERE id = '$product_id'");
                                $productRow  = mysqli_fetch_array($productsSql);

                                if (!$productRow) {
                                    continue;
                                }

                                $productName        = $productRow['name'];
                                $productImage       = $productRow['image'];
                                $productDescription = $productRow['description'];
                                $category_id        = $productRow['category_id'];

                                $categorySql = mysqli_query($con, "SELECT * FROM categories WHERE id = '$category_id'");
                                $categoryRow = mysqli_fetch_array($categorySql);
                                $categoryName = $categoryRow ? $categoryRow['name'] : '';
                        ?>
                        <div class="col-md-6 col-lg-6 col-xl-4" id="fav-card-<?php echo $product_id; ?>">
                            <div class="rounded position-relative fruite-item">
                                <?php if ($B_ID) { ?>
                                    <i id="icon-<?php echo $product_id; ?>"
                                       onclick="addToFav(<?php echo $B_ID; ?>, <?php echo $product_id; ?>)"
                                       class="fa fa-heart love-icon"></i>
                                <?php } ?>
                                <div class="fruite-img">
                                    <img src="../Seller_Dashboard/<?php echo $productImage; ?>" class="img-fluid w-100 rounded-top" alt="">
                                </div>
                                <div class="text-white background-sec px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $categoryName; ?></div>
                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                    <h4><?php echo $productName; ?></h4>
                                    <p><?php echo substr($productDescription, 0, 10); ?>...</p>
                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                        <p class="text-dark fs-5 fw-bold mb-0"></p>
                                        <a href="./Product.php?product_id=<?php echo $product_id; ?>" class="btn border border-secondary rounded-pill px-3 text-primary">View Product</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Favorites End -->

    <!-- Footer Start -->
    <?php require './Footer.php'; ?>
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

    <!-- Shared favorite toggle function -->
    <script>
        const addToFav = (customerId, productId) => {
            fetch(`./AddToFavorite.php?buyer_id=${customerId}&product_id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        if (data.message) alert(data.message);
                        return;
                    }

                    const icon = document.getElementById(`icon-${productId}`);
                    if (icon) {
                        if (data.action === 'added') {
                            icon.classList.remove('not-fav');
                            icon.classList.add('love-icon');
                        } else if (data.action === 'removed') {
                            icon.classList.remove('love-icon');
                            icon.classList.add('not-fav');
                        }
                    }

                    // Remove card from favorites page when unfavorited
                    if (data.action === 'removed') {
                        const card = document.getElementById(`fav-card-${productId}`);
                        if (card) card.remove();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong, please try again.');
                });
        };
    </script>

</body>
</html>
