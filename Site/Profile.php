<?php
session_start();
include "../Connect.php";

// 1) Must be logged in
if (!isset($_SESSION['B_ID']) || empty($_SESSION['B_ID'])) {
    header("Location: ../Login.php");
    exit;
}

$B_ID = (int) $_SESSION['B_ID'];

// 2) Default values (so you NEVER get undefined variable warnings)
$name = $email = $phone = $password = '';
$cart_count = 0;

// cart count
$sql1 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
if ($sql1) {
    $row1 = mysqli_fetch_assoc($sql1);
    $cart_count = (int)($row1['cart_count'] ?? 0);
}

// user info (IMPORTANT: check result before using it)
$sql2 = mysqli_query($con, "SELECT name,email,phone,password FROM users WHERE id = '$B_ID' LIMIT 1");
if ($sql2 && mysqli_num_rows($sql2) > 0) {
    $row2 = mysqli_fetch_assoc($sql2);
    $name     = $row2['name'] ?? '';
    $email    = $row2['email'] ?? '';
    $phone    = $row2['phone'] ?? '';
    $password = $row2['password'] ?? '';
} else {
    // user not found -> force logout
    session_destroy();
    header("Location: ../Login.php");
    exit;
}

// 3) Update profile (DON'T take B_ID from POST)
if (isset($_POST['Submit'])) {
    $name     = $_POST['name'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $con->prepare("UPDATE users SET name = ?, password = ?, phone = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $password, $phone, $email, $B_ID);

    if ($stmt->execute()) {
        echo "<script>alert('Account Updated Successfully !');</script>";
        echo "<script>document.location='./Profile.php';</script>";
        exit;
    } else {
        // If you still get HTTP 500, this will show the real DB error:
        die("Update failed: " . $stmt->error);
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
                            <?php if($B_ID) {?>
                                <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                                <?php }?>
                            <?php if ($B_ID) {?>
                                <a href="Orders.php" class="nav-item nav-link">Orders</a>
                            <?php }?>
                            <?php if (! $B_ID) {?>
                            <a href="../Login.php" class="nav-item nav-link">Login</a>
                            <?php } else {?>
                                <a href="./Logout.php" class="nav-item nav-link">Logout</a>
                            <?php } ?>
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
            <h1 class="text-center text-white display-6">Profile</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item active text-white" style="color: #fff !important;">Profile</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Account Info</h1>
                <form action="./Profile.php" method="POST">
                    <input type="hidden" name="B_ID" value="<?php echo $B_ID ?>">
                    <div class="row g-5">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-item w-100">
                                        <label class="form-label my-3">Full Name<sup>*</sup></label>
                                        <input type="text" name="name" class="form-control" value="<?php echo $name ?>" required>
                                    </div>
                                </div>

                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Email<sup>*</sup></label>
                                <input type="email" name="email" class="form-control" value="<?php echo $email ?>" required>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Phone <sup>*</sup></label>
                                <input type="text" name="phone" pattern="[0-9]{10}" title="Phone Number Must Be 10 Numbers" class="form-control" value="<?php echo $phone ?>" required>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Password<sup>*</sup></label>
                                <input type="text" name="password" class="form-control" value="<?php echo $password ?>" required>
                            </div>

                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">

                        <button class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary" type="submit" name="Submit">Save</button>
                    </div>


                        </div>

                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->


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