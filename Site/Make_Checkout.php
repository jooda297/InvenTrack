<?php

include "../Connect.php";
require_once './Socket.php';

session_start();
$B_ID = $_SESSION['B_ID'];

use PHPMailer\PHPMailer\PHPMailer;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

$buyer_id = $_GET['B_ID'];

$cart = [];
$productsList = [];

$cartSql = mysqli_query($con, "SELECT * from carts WHERE buyer_id = '$buyer_id'");

$totalPrice = 0;

while ($cartRow = mysqli_fetch_array($cartSql)) {

    $product_id = $cartRow['product_id'];
    $qty = $cartRow['qty'];

    $productSql = mysqli_query($con, "SELECT seller_id, price from products WHERE id = '$product_id'");
    $productRow = mysqli_fetch_array($productSql);

    $seller_id = $productRow['seller_id'];

    $price = $productRow['price'];

    $totalPrice += ($price * $qty);

    $cart[$seller_id][] = [
        'cart_id' => $cartRow['id'],
        'product_id' => $cartRow['product_id'],
        'seller_id' => $seller_id,
        'price' => $price,
        'options' => $cartRow['options'],
        'qty' => $cartRow['qty'],
    ];

}

$stmt = $con->prepare("INSERT INTO orders (buyer_id, total_price) VALUES (?, ?) ");

$stmt->bind_param("id", $buyer_id, $totalPrice);

if ($stmt->execute()) {

    $order_id = $stmt->insert_id;

    foreach ($cart as $sellerId => $list) {

        foreach ($list as $item) {

            $product_id = $item['product_id'];
            $options = $item['options'];
            $qty = $item['qty'];
            $cart_id = $item['cart_id'];
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

                    $newQty = $product_qty - $qty;
if ($newQty < 0) $newQty = 0;

$updateProductStmt = $con->prepare("UPDATE products SET qty = ? WHERE id = ?");
$updateProductStmt->bind_param("ii", $newQty, $product_id);

if ($updateProductStmt->execute()) {
    notify_socket_server($product_id, $newQty, $sellerId);

    $deleteFromCartStmt = $con->prepare("DELETE FROM carts WHERE id = ?");
    $deleteFromCartStmt->bind_param("i", $cart_id);
    $deleteFromCartStmt->execute();

    // keep the rest of your code as-is...
}

                        $sellersSql = mysqli_query($con, "select * from users where id='$sellerId'");
                        $sellerRow = mysqli_fetch_array($sellersSql);

                        $sellerEmail = $sellerRow['email'];

                        if ($newQty <= 2) {

                            $exists = false;

                            foreach ($productsList as $product) {
                                if ($product['id'] == $product_id) {
                                    $exists = true;
                                    break;
                                }
                            }

                            if (!$exists) {

                                $productsList[] = [
                                    'id' => $product_id,
                                    'name' => $product_name,
                                    'seller_email' => $sellerEmail,
                                ];
                            }
                        }
                    }
                }
            }

        }
    }

    try {

        foreach ($productsList as $product) {

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'inventrack4@gmail.com';
            $mail->Password = 'zqttrhuztxkucbxi';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom("inventrack4@gmail.com");
            $mail->addAddress($product['seller_email']);

            $productName = $product['name'];

            $mail->Subject = "Product Warning Request";
            $mail->Body = "Please be informed this product {$productName}, has only quantity of 2";

            $mail->send();

        }

    } catch (Exception $e) {

        echo $e->getMessage();
        die;
    }


echo "<script language='JavaScript'>
alert ('Thank you for dealing with Inventrack, Your Order Has Been Placed !');
</script>";

echo "<script language='JavaScript'>
document.location='./Orders.php';
</script>";