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
                    <a href="Offers.php" class="nav-item nav-link">Offers</a>
                    <?php if ($B_ID) { ?>
                        <a href="Favorites.php" class="nav-item nav-link">Favorites</a>
                        <a href="Orders.php" class="nav-item nav-link">Orders</a>
                        <a href="OrderHistory.php" class="nav-item nav-link active">Order History</a>
                    <?php } ?>
                    <?php if (! $B_ID) { ?>
                        <a href="../Login.php" class="nav-item nav-link">Login</a>
                    <?php } else { ?>
                        <a href="./Logout.php" class="nav-item nav-link">Logout</a>
                    <?php } ?>
                </div>

                <?php if ($B_ID) { ?>
                <div class="d-flex m-3 me-0">
                    <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4"
                            data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search text-primary"></i>
                    </button>
                    <a href="./Cart.php" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                              style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cart_count ?></span>
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

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Order History</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="./index.php" style="color:#fff !important;">Home</a></li>
        <li class="breadcrumb-item active text-white" style="color:#fff !important;">Order History</li>
    </ol>
</div>

<!-- Order History Table -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Seller Name</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Items</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // ONLY delivered orders (status_id = 3)
                $sql33 = mysqli_query(
                    $con,
                    "SELECT * FROM orders
                     WHERE buyer_id = '$B_ID' AND status_id = 3
                     ORDER BY created_at DESC"
                );

                while ($row33 = mysqli_fetch_array($sql33)) {

                    $totalPrice = 0;

                    $order_id    = $row33['id'];
                    $offer_id    = $row33['offer_id'];
                    $status_id   = $row33['status_id'];
                    $seller_id   = $row33['seller_id'];
                    $total_price = $row33['total_price'];
                    $created_at  = $row33['created_at'];

                    // format day-month-year
                    $created_at_formatted = date('d-m-Y', strtotime($created_at));

                    $sql88 = mysqli_query($con, "SELECT name AS status_name FROM statuses WHERE id = '$status_id'");
                    $row88 = mysqli_fetch_array($sql88);
                    $status_name = $row88['status_name'];

                    $sql77 = mysqli_query($con, "SELECT name AS seller_name FROM users WHERE id = '$seller_id'");
                    $row77 = mysqli_fetch_array($sql77);
                    $seller_name = $row77['seller_name'];

                    $sql55 = mysqli_query($con, "SELECT product_id, option_id, quantity, product_price FROM order_items WHERE order_id = '$order_id'");
                    while ($row55 = mysqli_fetch_array($sql55)) {
                        $product_id    = $row55['product_id'];
                        $options       = json_decode($row55['option_id'], true);
                        $color_id      = $options['color_id'];
                        $size_id       = $options['size_id'];
                        $quantity      = $row55['quantity'];
                        $product_price = $row55['product_price'];

                        $totalPrice += ($product_price * $quantity);
                    }
                ?>
                <tr>
                    <td><p class="mb-0 mt-4"><?php echo $order_id; ?></p></td>
                    <td><p class="mb-0 mt-4"><?php echo $seller_name; ?></p></td>
                    <td><p class="mb-0 mt-4"><?php echo $totalPrice; ?> JODs</p></td>
                    <td><p class="mb-0 mt-4"><?php echo $created_at_formatted; ?></p></td>
                    <td><p class="mb-0 mt-4"><?php echo $status_name; ?></p></td>
                    <td>
                        <button onclick="onClick(event)"
                                data-bs-toggle="modal"
                                data-bs-target="#verticalycentered"
                                class="btn btn-success"
                                id="btn-<?php echo $order_id; ?>">
                            View Items
                        </button>
                    </td>
                    <td>
                        <!-- Only Rate button for delivered orders (optional) -->
                        <a href="./Rate-Form.php?seller_id=<?php echo $seller_id; ?>&order_id=<?php echo $order_id; ?>"
                           class="btn border-secondary py-2 px-3 text-uppercase text-primary">
                            Rate
                        </a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Modal for Items (same as Orders.php) -->
        <div class="modal fade" id="verticalycentered" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Options</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Total</th>
                                </tr>
                                </thead>
                                <tbody id="modaltbody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require './Footer.php' ?>

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top">
    <i class="fa fa-arrow-up"></i>
</a>

<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<script>
const onClick = (e) => {
    $('#modaltbody').empty();
    const orderId = e.target.id.split('btn-')[1];

    fetch(`./Get_order_items.php?order_id=${orderId}`)
        .then(res => res.json())
        .then(res => {
            res.forEach(item => {
                let itemHtml = `
                    <tr>
                        <td><p class="mb-0 mt-4">${item.prroduct_name}</p></td>
                        <td><p class="mb-0 mt-4">${item.price} JODs</p></td>
                        <td><p class="mb-0 mt-4">${item.color_value ?? ''} ${item.size_value ?? ''}</p></td>
                        <td><p class="mb-0 mt-4">${item.qty}</p></td>
                        <td><p class="mb-0 mt-4">${item.price * item.qty} JODs</p></td>
                    </tr>`;
                $('#modaltbody').append(itemHtml);
            });
        });
};
</script>

<script src="js/main.js"></script>
</body>
</html>
