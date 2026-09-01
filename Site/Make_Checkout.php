<?php

include "../Connect.php";
require_once './Socket.php';

session_start();
$B_ID = $_SESSION['B_ID'];



$buyer_id = $_GET['B_ID'];

$cart = [];


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

               $productStmt = $con->prepare(
    "SELECT name, qty FROM products WHERE id = ?"
);

$productStmt->bind_param("i", $product_id);

$productStmt->execute();
$productStmt->store_result();

if ($productStmt->num_rows > 0) {

    // Initialize variables before bind_result
    $product_name = '';
    $currentStock = 0;

    // name goes into $product_name
    // qty goes into $currentStock
    $productStmt->bind_result($product_name, $currentStock);
    $productStmt->fetch();

    // Current inventory - amount customer purchased
    $newQty = $currentStock - $qty;

    if ($newQty < 0) {
        $newQty = 0;
    }

    $updateProductStmt = $con->prepare(
        "UPDATE products SET qty = ? WHERE id = ?"
    );

    $updateProductStmt->bind_param(
        "ii",
        $newQty,
        $product_id
    );

if ($updateProductStmt->execute()) {
    notify_socket_server($product_id, $newQty, $sellerId);

    $deleteFromCartStmt = $con->prepare("DELETE FROM carts WHERE id = ?");
    $deleteFromCartStmt->bind_param("i", $cart_id);
    $deleteFromCartStmt->execute();

   
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