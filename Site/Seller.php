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
$sellerImage       = $row2['image'];
$sellerDescription = $row2['description'];
$instagram_link    = $row2['instagram_link'];


if (isset($_POST['Submit'])) {

    // must be logged in
    if (!$B_ID) {

        echo "<script>alert('Please login first');</script>";
        echo "<script>document.location='../Login.php';</script>";

        exit;
    }


  $feedback = trim($_POST['feedback'] ?? '');
$seller_post_id = (int)($_POST['seller_id'] ?? 0);


 if ($seller_post_id <= 0 || $feedback === '') {

    echo "<script>alert('Please write a review first.');</script>";
    echo "<script>document.location='./Seller.php?seller_id={$seller_post_id}&tab=reviews';</script>";

    exit;
}


    $stmt = $con->prepare(
        "INSERT INTO seller_feedbacks (buyer_id, seller_id, feedback)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "iis",
        $B_ID,
        $seller_post_id,
        $feedback
    );

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

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <meta content="" name="keywords">

    <meta content="" name="description">


    <link
        href="../assets/img/icon.png"
        rel="icon"
    >

    <link
        href="../assets/img/icon.png"
        rel="apple-touch-icon"
    >


    <!-- Google Web Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- Icon Font Stylesheet -->

    <link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <!-- Libraries Stylesheet -->

    <link
        href="lib/lightbox/css/lightbox.min.css"
        rel="stylesheet"
    >

    <link
        href="lib/owlcarousel/assets/owl.carousel.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap -->

    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Main Template CSS -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >


    <!-- Seller Page CSS -->

    <style>

        /* =====================================================
           SELLER PAGE GENERAL
        ===================================================== */

        .seller-page {
            background: #f8fafc;
        }


        .seller-content-container {
            padding-top: 20px;
            padding-bottom: 50px;
        }


        /* =====================================================
           SELLER PROFILE CARD
        ===================================================== */

        .seller-main-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            height: 100%;
        }


        .seller-image-box {
            width: 100%;
            max-width: 410px;
            aspect-ratio: 1 / 1;

            margin: auto;

            overflow: hidden;

            background: #f5f7f8;

            border-radius: 22px;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        .seller-image-box img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .seller-info {
            padding: 20px 10px;
        }


        .seller-info h2 {
            font-family: "Raleway", sans-serif;

            font-size: 32px;
            font-weight: 800;

            color: #425b5b;

            margin-bottom: 20px;
        }


        .seller-phone {
            font-size: 16px;

            color: #6b7b83;

            margin-bottom: 18px;
        }


        .seller-phone i {
            color: #0b4f8a;
        }


        .seller-instagram {
            width: 44px;
            height: 44px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            color: #0b4f8a;

            font-size: 19px;

            text-decoration: none;

            transition: all 0.25s ease;
        }


        .seller-instagram:hover {
            background: #073a67;

            color: #ffffff;

            transform: translateY(-2px);
        }


      

        /* 
           SELLER TABS
         */

        .seller-tabs {
            margin-top: 35px;
        }


        .seller-tabs .nav-tabs {
            border-bottom: 1px solid #e5e9ec;
        }


        .seller-tabs .nav-link {
            border: none !important;

            background: transparent;

            color: #52656b;

            font-weight: 600;

            padding: 12px 18px;

            margin-right: 5px;

            border-radius: 0 !important;
        }


        .seller-tabs .nav-link.active {
            color: #0b4f8a;

            border-bottom: 3px solid #0b4f8a !important;
        }


        .seller-tab-content {
            padding: 22px 5px 5px;

            color: #6c7a80;

            line-height: 1.8;
        }


        /* review list */

        .seller-review {
            display: flex;

            gap: 15px;

            padding: 18px 0;

            border-bottom: 1px solid #eeeeee;
        }


        .seller-review:last-child {
            border-bottom: none;
        }


        .seller-review-avatar {
            width: 65px;
            height: 65px;

            flex-shrink: 0;

            border-radius: 50%;

            object-fit: cover;
        }


        .seller-review-date {
            color: #9aa5ab;

            font-size: 13px;

            margin-bottom: 4px;
        }


        .seller-review h5 {
            color: #425b5b;

            margin-bottom: 5px;
        }


        /* 
           FEATURED PRODUCTS
        */

        .featured-products-box {
            background: #ffffff;

            padding: 25px;

            border-radius: 22px;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);

            height: fit-content;
        }


        .featured-products-box h4 {
            color: #425b5b;

            font-family: "Raleway", sans-serif;

            font-size: 24px;
            font-weight: 800;

            margin-bottom: 20px;
        }


        .featured-product-item {
            display: flex;

            align-items: center;

            gap: 14px;

            padding: 14px 0;

            border-bottom: 1px solid #eeeeee;
        }


        .featured-product-item:last-child {
            border-bottom: none;
        }


        .featured-product-image {
            width: 80px;
            height: 80px;

            flex-shrink: 0;

            border-radius: 14px;

            overflow: hidden;

            background: #f6f6f6;
        }


        .featured-product-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .featured-product-info {
            min-width: 0;
        }


        .featured-product-info a {
            text-decoration: none;
        }


        .featured-product-info h6 {
            color: #425b5b;

            font-size: 15px;
            font-weight: 700;

            margin-bottom: 5px;
        }


        .featured-product-stars {
            display: flex;

            gap: 2px;

            margin-bottom: 5px;
        }


        .featured-product-price {
            color: #425b5b;

            font-size: 16px;
            font-weight: 700;

            margin: 0;
        }


        /*
           PRODUCTS
        */

        .seller-products-section {
            margin-top: 55px;
        }


        .seller-products-title {
            color: #425b5b;

            font-family: "Raleway", sans-serif;

            font-size: 34px;
            font-weight: 800;

            margin-bottom: 25px;
        }


        .product-grid {
            width: 100%;

            display: grid;

            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: 24px;
        }


        .product-grid .vesitable-item {
            position: relative;

            height: 100%;

            display: flex;

            flex-direction: column;

            overflow: hidden;

            background: #ffffff;

            border: none !important;

            border-radius: 20px !important;

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }


        .product-grid .vesitable-item:hover {
            transform: translateY(-5px);

            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12);
        }


        .product-grid .vesitable-img {
            width: 100%;

            height: 220px !important;

            overflow: hidden;

            background: #f5f5f5;
        }


        .product-grid .vesitable-img img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            border-radius: 0 !important;
        }


        .product-category {
            position: absolute;

            top: 12px;
            right: 12px;

            background: #0b4f8a;

            color: #ffffff;

            border-radius: 25px;

            padding: 6px 16px;

            font-size: 14px;
        }


        .product-card-content {
            padding: 20px;

            flex: 1;

            display: flex;

            flex-direction: column;
        }


        .product-card-content h4 {
            color: #425b5b;

            font-size: 21px;

            font-weight: 700;

            margin-bottom: 8px;
        }


        .product-description {
            color: #77858b;

            font-size: 14px;

            min-height: 22px;

            margin-bottom: 18px;
        }


        .product-bottom {
            margin-top: auto;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;
        }


        .product-price {
            color: #425b5b;

            font-size: 18px;

            font-weight: 700;

            margin: 0;
        }


        .product-view-btn {
            border: 2px solid #0b4f8a;

            color: #0b4f8a;

            background: #ffffff;

            border-radius: 25px;

            padding: 7px 15px;

            font-size: 14px;

            font-weight: 600;

            white-space: nowrap;

            text-decoration: none;

            transition: all 0.2s ease;
        }


        .product-view-btn:hover {
            background: #0b4f8a;

            color: #ffffff;
        }


        /* 
           REVIEW FORM
       */

        .review-section {
            margin-top: 55px;

            margin-bottom: 35px;
        }


        .review-card {
            background: #ffffff;

            border-radius: 22px;

            padding: 30px;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }


        .review-card h4 {
            color: #425b5b;

            font-family: "Raleway", sans-serif;

            font-size: 26px;

            font-weight: 800;

            margin-bottom: 20px;
        }


        .review-card textarea {
            width: 100%;

            min-height: 140px;

            resize: vertical;

            padding: 18px;

            color: #56666c;

            background: #f8fafc;

            border: 1px solid #e1e6e9 !important;

            border-radius: 14px !important;

            outline: none;

            box-shadow: none !important;
        }


        .review-card textarea:focus {
            background: #ffffff;

            border-color: #0b4f8a !important;
        }


        .review-submit {
            display: flex;

            justify-content: flex-end;

            margin-top: 18px;
        }


        .review-submit button {
            border: 2px solid #0b4f8a !important;

            color: #0b4f8a !important;

            background: #ffffff;

            border-radius: 30px;

            padding: 10px 24px;

            font-weight: 600;

            transition: 0.2s ease;
        }


        .review-submit button:hover {
            color: #ffffff !important;

            background: #0b4f8a;
        }

      

/*RESPONSIVE*/

        @media (max-width: 1199px) {

            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

        }


        @media (max-width: 991px) {

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }


            .seller-info {
                text-align: center;
            }


           


            .seller-main-card {
                padding: 22px;
            }


            .featured-products-box {
                margin-top: 5px;
            }

        }


        @media (max-width: 575px) {

            .product-grid {
                grid-template-columns: 1fr;
            }


            .seller-products-title {
                font-size: 28px;
            }


            .seller-info h2 {
                font-size: 27px;
            }


            .seller-main-card,
            .featured-products-box,
            .review-card {
                padding: 20px;
            }


            .product-bottom {
                align-items: flex-start;

                flex-direction: column;
            }

        }

    </style>


</head>


<body>


<!-- =====================================================
     SPINNER
===================================================== -->

<div
    id="spinner"
    class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center"
>

    <div
        class="spinner-grow text-primary"
        role="status"
    ></div>

</div>



<!-- =====================================================
     NAVBAR
===================================================== -->

<div
    class="container-fluid fixed-top"
    style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;"
>

    <div class="container px-0">

        <nav class="navbar navbar-light bg-white navbar-expand-xl">


            <a
                href="index.php"
                class="navbar-brand d-flex align-items-center"
            >

                <img
                    src="../assets/img/Logo.png"
                    class="logo-inventrack"
                    alt="Inventrack"
                >

            </a>


            <button
                class="navbar-toggler py-2 px-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse"
            >

                <span class="fa fa-bars text-primary"></span>

            </button>


            <div
                class="collapse navbar-collapse bg-white"
                id="navbarCollapse"
            >


                <div class="navbar-nav mx-auto">


                    <a
                        href="index.php"
                        class="nav-item nav-link active"
                    >
                        Home
                    </a>


                    <a
                        href="Products.php"
                        class="nav-item nav-link"
                    >
                        Products
                    </a>


                    <a
                        href="Sellers.php"
                        class="nav-item nav-link"
                    >
                        Sellers
                    </a>


                    <?php if ($B_ID) { ?>

                        <a
                            href="Favorites.php"
                            class="nav-item nav-link"
                        >
                            Favorites
                        </a>

                    <?php } ?>


                  


                    <?php if ($B_ID) { ?>

                        <a
                            href="Orders.php"
                            class="nav-item nav-link"
                        >
                            Orders
                        </a>

                    <?php } ?>


                    <?php if (!$B_ID) { ?>

                        <a
                            href="../Login.php"
                            class="nav-item nav-link"
                        >
                            Login
                        </a>

                    <?php } else { ?>

                        <a
                            href="./Logout.php"
                            class="nav-item nav-link"
                        >
                            Logout
                        </a>

                    <?php } ?>


                </div>


                <?php if ($B_ID) { ?>


                    <div class="d-flex m-3 me-0">


                        <button
                            class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4"
                            data-bs-toggle="modal"
                            data-bs-target="#searchModal"
                        >

                            <i class="fas fa-search text-primary"></i>

                        </button>


                        <a
                            href="./Cart.php"
                            class="position-relative me-4 my-auto"
                        >

                            <i class="fa fa-shopping-bag fa-2x"></i>


                            <span
                                class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                                style="top: -5px; left: 15px; height: 20px; min-width: 20px;"
                            >

                                <?php echo $cart_count ?>

                            </span>

                        </a>


                        <a
                            href="./Profile.php"
                            class="my-auto"
                        >

                            <i class="fas fa-user fa-2x"></i>

                        </a>


                    </div>


                <?php } ?>


            </div>


        </nav>


    </div>


</div>



<!-- =====================================================
     SEARCH MODAL
===================================================== -->

<div
    class="modal fade"
    id="searchModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-fullscreen">


        <div class="modal-content rounded-0">


            <div class="modal-header">


                <h5
                    class="modal-title"
                    id="exampleModalLabel"
                >
                    Search by keyword
                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>


            </div>


            <div class="modal-body d-flex align-items-center">


                <div class="input-group w-75 mx-auto d-flex">


                    <form
                        class="w-75 mx-auto d-flex"
                        action="./Products.php"
                        method="POST"
                    >


                        <input
                            type="search"
                            class="form-control p-3"
                            name="product_name"
                            placeholder="product name"
                            aria-describedby="search-icon-1"
                        >


                        <button
                            type="submit"
                            id="search-icon-1"
                            class="input-group-text p-3"
                        >

                            <i class="fa fa-search"></i>

                        </button>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>



<!-- =====================================================
     PAGE HEADER
===================================================== -->

<div class="container-fluid page-header py-5">


    <h1 class="text-center text-white display-6">

        <?php echo $sellerName ?> Detail

    </h1>


    <ol class="breadcrumb justify-content-center mb-0">


        <li class="breadcrumb-item">

            <a
                href="./index.php"
                style="color: #fff !important;"
            >
                Home
            </a>

        </li>


        <li class="breadcrumb-item">

            <a
                href="./Sellers.php"
                style="color: #fff !important;"
            >
                Sellers
            </a>

        </li>


        <li
            class="breadcrumb-item active text-white"
            style="color: #fff !important;"
        >

            <?php echo $sellerName ?> Detail

        </li>


    </ol>


</div>



<!-- =====================================================
     SELLER PAGE
===================================================== -->

<div class="container-fluid seller-page py-5">


    <div class="container seller-content-container">


        <div class="row g-4 align-items-start">


            <!-- seller info left-->

            <div class="col-lg-8 col-xl-9">


                <div class="seller-main-card">


                    <div class="row g-4 align-items-center">


                        <!-- Seller Image -->

                        <div class="col-lg-6">


                            <div class="seller-image-box">


                                <img
                                    src="../Seller_Dashboard/<?php echo $sellerImage ?>"
                                    alt="<?php echo $sellerName ?>"
                                >


                            </div>


                        </div>


                        <!-- Seller Information -->

                        <div class="col-lg-6">


                            <div class="seller-info">


                                <h2>

                                    <?php echo $sellerName ?>

                                </h2>


                                <p class="seller-phone">

                                    <i class="fas fa-phone-alt me-2"></i>

                                    <?php echo $sellerPhone ?>

                                </p>


                                <?php if (!empty($instagram_link)) { ?>


                                    <a
                                        href="<?php echo $instagram_link ?>"
                                        target="_blank"
                                        class="seller-instagram"
                                        title="Instagram"
                                    >

                                        <i class="bi bi-instagram"></i>

                                    </a>


                                <?php } ?>


                   


                            </div>


                        </div>


                    </div>



                    <!-- =================================================
                         DESCRIPTION / REVIEWS
                    ================================================== -->

                    <div class="seller-tabs">


                        <nav>


                            <div class="nav nav-tabs">


                                <button
                                    class="nav-link active"
                                    type="button"
                                    role="tab"
                                    id="nav-about-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#nav-about"
                                    aria-controls="nav-about"
                                    aria-selected="true"
                                >

                                    Description

                                </button>


                                <button
                                    class="nav-link"
                                    type="button"
                                    role="tab"
                                    id="nav-mission-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#nav-mission"
                                    aria-controls="nav-mission"
                                    aria-selected="false"
                                >

                                    Reviews

                                </button>


                            </div>


                        </nav>


                        <div class="tab-content seller-tab-content">


                            <!-- Description -->

                            <div
                                class="tab-pane fade show active"
                                id="nav-about"
                                role="tabpanel"
                                aria-labelledby="nav-about-tab"
                            >


                                <p>

                                    <?php echo $sellerDescription ?>

                                </p>


                            </div>



                            <!-- Reviews -->

                            <div
                                class="tab-pane fade"
                                id="nav-mission"
                                role="tabpanel"
                                aria-labelledby="nav-mission-tab"
                            >


                                <?php


                                $sql33 = mysqli_query(
                                    $con,
                                    "SELECT * from seller_feedbacks
                                     WHERE seller_id = '$seller_id'"
                                );


                                while ($row33 = mysqli_fetch_array($sql33)) {


                                    $feedback_id = $row33['id'];

                                    $buyer_id = $row33['buyer_id'];

                                    $feedback = $row33['feedback'];

                                    $created_at = $row33['created_at'];


                                    $sql44 = mysqli_query(
                                        $con,
                                        "SELECT * from users
                                         WHERE id = '$buyer_id'"
                                    );


                                    $row44 = mysqli_fetch_array($sql44);


                                    $buyer_name = $row44['name'];


                                   


                                ?>


                                    <div class="seller-review">


                                        <img
                                            src="img/avatar.jpg"
                                            class="seller-review-avatar"
                                            alt="User"
                                        >


                                        <div>


                                            <p class="seller-review-date">

                                                <?php echo $created_at ?>

                                            </p>


                                            <h5>

                                                <?php echo $buyer_name ?>

                                            </h5>




                                            <p>

                                                <?php echo $feedback ?>

                                            </p>


                                        </div>


                                    </div>


                                <?php } ?>


                            </div>


                        </div>


                    </div>


                </div>


            </div>



            <!-- =================================================
                 RIGHT SIDE - FEATURED PRODUCTS
            ================================================== -->

            <div class="col-lg-4 col-xl-3">


                <div class="featured-products-box">


                    <h4>

                        Featured Products

                    </h4>


                    <?php


                    $sql223 = mysqli_query(
                        $con,
                        "SELECT *
                         from products
                         WHERE active = 1
                         AND total_rate >= 2
                         AND seller_id = '$seller_id'"
                    );


                    while ($row223 = mysqli_fetch_array($sql223)) {


                        $product_id_featured = $row223['id'];

                        $product_name_featured = $row223['name'];

                        $product_image_featured = $row223['image'];

                        $product_total_rate_featured = $row223['total_rate'];

                        $product_price_featured = $row223['price'];


                    ?>


                        <div class="featured-product-item">


                            <div class="featured-product-image">


                                <img
                                    src="../Seller_Dashboard/<?php echo $product_image_featured ?>"
                                    alt="<?php echo $product_name_featured ?>"
                                >


                            </div>


                            <div class="featured-product-info">


                                <a
                                    href="./Product.php?product_id=<?php echo $product_id_featured ?>"
                                >


                                    <h6>

                                        <?php echo $product_name_featured ?>

                                    </h6>


                                </a>


                              <div class="featured-product-stars">


                                    <?php


                                    for (
                                        $iiii = 1;
                                        $iiii < $product_total_rate_featured;
                                        $iiii++
                                    ) {

                                    ?>


                                        <i class="fa fa-star text-secondary"></i>


                                    <?php } ?>


                                </div> 


                                <p class="featured-product-price">

                                    <?php echo $product_price_featured ?> JODs

                                </p>


                            </div>


                        </div>


                    <?php } ?>


                </div>


            </div>


        </div>



        <!-- =====================================================
             SELLER PRODUCTS
        ====================================================== -->

        <div class="seller-products-section">


            <h2 class="seller-products-title">

                <?php echo $sellerName ?> Products

            </h2>


            <div class="product-grid">


                <?php


                $sql55 = mysqli_query(
                    $con,
                    "SELECT *
                     from products
                     WHERE active = 1
                     AND seller_id = '$seller_id'"
                );


                while ($row55 = mysqli_fetch_array($sql55)) {


                    $product_id = $row55['id'];

                    $category_id = $row55['category_id'];

                    $product_name = $row55['name'];

                    $product_image = $row55['image'];

                    $product_price = $row55['price'];

                    $product_description = $row55['description'];


                    $sql66 = mysqli_query(
                        $con,
                        "SELECT *
                         from categories
                         WHERE id = '$category_id'"
                    );


                    $row66 = mysqli_fetch_array($sql66);


                    $category_name = $row66['name'];


                ?>


                    <div
                        class="vesitable-item"
                        id="<?php echo $product_id ?>"
                    >


                        <!-- Product Image -->

                        <div class="vesitable-img">


                            <img
                                src="../Seller_Dashboard/<?php echo $product_image ?>"
                                alt="<?php echo $product_name ?>"
                            >


                        </div>


                        <!-- Category -->

                        <div class="product-category">

                            <?php echo $category_name ?>

                        </div>


                        <!-- Product Details -->

                        <div class="product-card-content">


                            <h4>

                                <?php echo $product_name ?>

                            </h4>


                            <p class="product-description">

                                <?php echo substr($product_description, 0, 40) ?>

                            </p>


                            <div class="product-bottom">


                                <p class="product-price">

                                    <?php echo $product_price ?> JODs

                                </p>


                                <a
                                    href="./Product.php?product_id=<?php echo $product_id ?>"
                                    class="product-view-btn"
                                >

                                    View Product

                                </a>


                            </div>


                        </div>


                    </div>


                <?php } ?>


            </div>


        </div>



        <!-- =====================================================
             REVIEW FORM
        ====================================================== -->

        <div class="review-section">


            <div class="review-card">


                <form
                    action="./Seller.php?seller_id=<?php echo $seller_id ?>&tab=reviews"
                    method="POST"
                >


                    <h4>

                        Leave a Review

                    </h4>

                    


                    <input
                        type="hidden"
                        name="seller_id"
                        value="<?php echo $seller_id ?>"
                    >


                    <textarea
                        name="feedback"
                        class="form-control"
                        placeholder="Write your review..."
                        spellcheck="false"
                    ></textarea>


                    <div class="review-submit">


                        <button
                            type="submit"
                            name="Submit"
                        >

                            Post Comment

                        </button>


                    </div>


                </form>


            </div>


        </div>


    </div>


</div>


<!-- Single Product End -->



<!-- =====================================================
     FOOTER
     KEEPING YOUR EXISTING FOOTER EXACTLY THE SAME
===================================================== -->

<?php require './Footer.php'?>



<!-- =====================================================
     BACK TO TOP
===================================================== -->

<a
    href="#"
    class="btn btn-primary border-3 border-primary rounded-circle back-to-top"
>

    <i class="fa fa-arrow-up"></i>

</a>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="lib/easing/easing.min.js"></script>

<script src="lib/waypoints/waypoints.min.js"></script>

<script src="lib/lightbox/js/lightbox.min.js"></script>

<script src="lib/owlcarousel/owl.carousel.min.js"></script>


<!-- Template Javascript -->

<script src="js/main.js"></script>



<!-- Open Reviews Tab After Comment -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const params =
            new URLSearchParams(
                window.location.search
            );


        if (params.get("tab") === "reviews") {


            const btn =
                document.querySelector(
                    'button[data-bs-target="#nav-mission"]'
                );


            if (btn) {


                if (
                    window.bootstrap &&
                    bootstrap.Tab
                ) {

                    bootstrap.Tab
                        .getOrCreateInstance(btn)
                        .show();

                } else {

                    btn.click();

                }


                btn.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }

        }

    }
);

</script>


</body>

</html>