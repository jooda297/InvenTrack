<?php
    session_start();

    include "../Connect.php";
    require_once './Socket.php';

    $B_ID = $_SESSION['B_ID'];

    use PHPMailer\PHPMailer\PHPMailer;

    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';

    if ($B_ID) {

        $sql211 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row211 = mysqli_fetch_array($sql211);

        $cart_count = $row211['cart_count'];

        if (isset($_POST['Submit'])) {

            $buyer_id = $_POST['B_ID'];

            $cart         = [];
            $productsList = [];

            $cartSql = mysqli_query($con, "SELECT * from carts WHERE buyer_id = '$buyer_id'");

            $totalPrice = 0;

            while ($cartRow = mysqli_fetch_array($cartSql)) {

                $product_id = $cartRow['product_id'];
                $qty        = $cartRow['qty'];

                $productSql = mysqli_query($con, "SELECT seller_id, price from products WHERE id = '$product_id'");
                $productRow = mysqli_fetch_array($productSql);

                $seller_id = $productRow['seller_id'];

                $price = $productRow['price'];

                $totalPrice += ($price * $qty);

                $cart[$seller_id][] = [
                    'cart_id'    => $cartRow['id'],
                    'product_id' => $cartRow['product_id'],
                    'seller_id'  => $seller_id,
                    'price'      => $price,
                    'options'    => $cartRow['options'],
                    'qty'        => $cartRow['qty'],
                ];

            }

            $stmt = $con->prepare("INSERT INTO orders (buyer_id, total_price) VALUES (?, ?) ");

            $stmt->bind_param("id", $buyer_id, $totalPrice);

            if ($stmt->execute()) {

                $order_id = $stmt->insert_id;

                foreach ($cart as $sellerId => $list) {

                    foreach ($list as $item) {

                        $product_id    = $item['product_id'];
                        $options       = $item['options'];
                        $qty           = $item['qty'];
                        $cart_id       = $item['cart_id'];
                        $product_price = $item['price'];

                        notify_order_socket($order_id, 1, $totalPrice, date("Y-m-d"), $sellerId);

                        $orderItemStmt = $con->prepare("INSERT INTO order_items (order_id, seller_id, product_id, option_id, quantity, product_price) VALUES (?, ?, ?, ?, ?, ?)");

                        $orderItemStmt->bind_param("iiisid", $order_id, $sellerId, $product_id, $options, $qty, $product_price);

                        if ($orderItemStmt->execute()) {

                            $productStmt = $con->prepare("SELECT name AS product_name, qty AS product_qty FROM products WHERE id = ?");
                            $productStmt->bind_param("i", $product_id);

                            $productStmt->execute();

                            $productStmt->store_result();

                            if ($productStmt->num_rows > 0) {

                                $productStmt->bind_result($product_name, $product_qty);
                                $productStmt->fetch();

                                $newQty = $product_qty - $item['qty'];
                                $out_of_stock = $newQty == 0;

                                $updateProductStmt = $con->prepare("UPDATE products SET qty = ? WHERE id = ?");
$updateProductStmt->bind_param("ii", $newQty, $product_id);


                                if ($updateProductStmt->execute()) {

                                    notify_socket_server($product_id, $newQty, $sellerId);

                                    $deleteFromCartStmt = $con->prepare("DELETE FROM carts WHERE id = ?");

                                    $deleteFromCartStmt->bind_param("i", $cart_id);
                                    $deleteFromCartStmt->execute();

                                    $sellersSql = mysqli_query($con, "select * from users where id='$sellerId'");
                                    $sellerRow  = mysqli_fetch_array($sellersSql);

                                    $sellerEmail = $sellerRow['email'];

                                    if ($newQty <= 2) {

                                        $exists = false;

                                        foreach ($productsList as $product) {
                                            if ($product['id'] == $product_id) {
                                                $exists = true;
                                                break;
                                            }
                                        }

                                        if (! $exists) {

                                            $productsList[] = [
                                                'id'           => $product_id,
                                                'name'         => $product_name,
                                                'seller_email' => $sellerEmail,
                                            ];
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }

            echo "<script language='JavaScript'>
                alert ('Thank you for dealing with Inventrack, Your Order Has Been Placed !');
           </script>";

            echo "<script language='JavaScript'>
          document.location='./Orders.php';
             </script>";
            // try {

            //     foreach ($productsList as $product) {

            //         $mail = new PHPMailer(true);

            //         $mail->isSMTP();
            //         $mail->Host       = 'smtp.gmail.com';
            //         $mail->SMTPAuth   = true;
            //         $mail->Username   = 'inventrack4@gmail.com';
            //         $mail->Password   = 'zqttrhuztxkucbxi';
            //         $mail->SMTPSecure = 'ssl';
            //         $mail->Port       = 465;

            //         $mail->setFrom("inventrack4@gmail.com");
            //         $mail->addAddress($product['seller_email']);

            //         $productName = $product['name'];

            //         $mail->Subject = "Product Warning Request";
            //         $mail->Body    = "Please be informed this product {$productName}, has only quantity of 2";

            //         $mail->send();

            //     }

            // } catch (Exception $e) {

            //     echo $e->getMessage();
            //     die;
            // }

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
                            <a href="index.php" class="nav-item nav-link ">Home</a>
                            <a href="Products.php" class="nav-item nav-link">Products</a>
                            <a href="Sellers.php" class="nav-item nav-link">Sellers</a>
                            <a href="Offers.php" class="nav-item nav-link">Offers</a>
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
            <h1 class="text-center text-white display-6">Checkout</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="./index.php" style="color: #fff !important;">Home</a></li>
                <li class="breadcrumb-item active text-white" style="color: #fff !important;">Checkout</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Billing details</h1>
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="POST" id="checkoutForm">


                <input type="hidden" name="B_ID" value="<?php echo $B_ID ?>" id="">





                    <div class="row g-5">

                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Products</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    <?php
                                        $sql33 = mysqli_query($con, "SELECT * from carts WHERE buyer_id = '$B_ID'");

                                        $totalPrice = 0;

                                        while ($row33 = mysqli_fetch_array($sql33)) {

                                            $cart_id    = $row33['id'];
                                            $product_id = $row33['product_id'];
                                            $options    = json_decode($row33['options'], true);
                                            $color_id   = $options['color_id'];
                                            $size_id    = $options['size_id'];
                                            $qty        = $row33['qty'];

                                            $sql55 = mysqli_query($con, "SELECT name, image, price, qty from products WHERE id = '$product_id'");
                                            $row55 = mysqli_fetch_array($sql55);

                                            $product_name  = $row55['name'];
                                            $product_image = $row55['image'];
                                            $product_price = $row55['price'];
                                            $product_qty   = $row55['qty'];

                                            if ($color_id != '') {

                                                $sql66 = mysqli_query($con, "SELECT value from product_options WHERE id = '$color_id'");
                                                $row66 = mysqli_fetch_array($sql66);

                                                $color_value = $row66['value'];
                                            }

                                            if ($size_id != '') {

                                                $sql77 = mysqli_query($con, "SELECT value from product_options WHERE id = '$size_id'");
                                                $row77 = mysqli_fetch_array($sql77);

                                                $size_value = $row77['value'];

                                            }

                                            $totalPrice += ($product_price * $qty);

                                        ?>




                                        <tr>
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img src="../Seller_Dashboard/<?php echo $product_image ?>" class="img-fluid rounded-circle" style="width: 90px; height: 90px;" alt="">
                                                </div>
                                            </th>
                                            <td class="py-5"><?php echo $product_name ?></td>
                                            <td class="py-5"><?php echo $product_price ?> JODs</td>
                                            <td class="py-5"><?php echo $qty ?></td>
                                            <td class="py-5"><?php echo $product_price * $qty ?> JODs</td>
                                        </tr>





                                        <?php }?>


                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark py-3">Subtotal</p>
                                            </td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark"><?php echo $totalPrice ?> JODs</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                            </th>
                                            <td class="py-5">
                                                <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                                            </td>
                                            <td class="py-5"></td>
                                            <td class="py-5"></td>
                                            <td class="py-5">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark"><?php echo $totalPrice + 1 ?> JODs</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <input type="hidden" name="cmd" value="_xclick">
                            <input type="hidden" name="business" value="sb-ps6it41795949@business.example.com">
                            <input type="hidden" name="currency_code" value="USD">
                            <input type="hidden" name="amount" value="<?php echo $totalPrice + 1; ?>">
                            <input type="hidden" name="return" value="http://localhost/Inventrack/Site/Make_Checkout.php?status=success&B_ID=<?php echo $B_ID ?>">
                            <input type="hidden" name="cancel_return" value="http://localhost/Inventrack/Site/index.php">









                            <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                                <div class="col-12">
                                    <div class="form-check text-start my-3">
                                    <input
        type="radio"
        class="form-check-input bg-primary border-0"
        id="payment-bank"
        name="payment_method"
        value="bank"
        checked
      >
                                        <label class="form-check-label" for="Transfer-1">Direct Bank Transfer</label>
                                    </div>
                                    <p class="text-start text-dark">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                                </div>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                                <div class="col-12">
                                    <div class="form-check text-start my-3">
                                    <input
        type="radio"
        class="form-check-input bg-primary border-0"
        id="payment-cod"
        name="payment_method"
        value="cod"
      >
                                        <label class="form-check-label" for="Delivery-1">Cash On Delivery</label>
                                    </div>
                                </div>
                            </div>


                            <script>
  (function(){
    // 1) grab elements
    var form     = document.getElementById('checkoutForm');
    var bank     = document.getElementById('payment-bank');
    var cod      = document.getElementById('payment-cod');

    // 2) define the two destinations
    var PAYPAL_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    var COD_URL    = './Checkout.php';  // change to your COD endpoint

    // 3) updater function
    function updateAction(){
      if (bank.checked) {
        form.action = PAYPAL_URL;
      } else if (cod.checked) {
        form.action = COD_URL;
      }
    }

    // 4) wire up change events
    bank.addEventListener('change', updateAction);
    cod.addEventListener('change', updateAction);

    // 5) set initial action on page-load
    updateAction();
  })();
                            </script>

                            <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary" name="Submit">Place Order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->


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
    </body>

</html>