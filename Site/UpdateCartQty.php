<?php
session_start();
include "../Connect.php";

if (!isset($_SESSION['B_ID'])) {
    header("Location: ../Login.php");
    exit;
}

$B_ID   = $_SESSION['B_ID'];
$cart_id = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;
$action  = isset($_GET['action']) ? $_GET['action'] : '';

if (!$cart_id || !in_array($action, ['inc', 'dec'])) {
    header("Location: Cart.php");
    exit;
}

// get current cart row (only for this buyer)
$res = mysqli_query($con, "SELECT qty, product_id FROM carts WHERE id = '$cart_id' AND buyer_id = '$B_ID'");
$row = mysqli_fetch_assoc($res);

if (!$row) {
    header("Location: Cart.php");
    exit;
}

$currentQty = (int)$row['qty'];
$product_id = (int)$row['product_id'];

// optional: check product stock
$stockRes = mysqli_query($con, "SELECT qty FROM products WHERE id = '$product_id'");
$stockRow = mysqli_fetch_assoc($stockRes);
$maxQty   = $stockRow ? (int)$stockRow['qty'] : 999999;

// handle actions
if ($action === 'inc') {
    if ($currentQty < $maxQty) {
        $newQty = $currentQty + 1;
    } else {
        $newQty = $currentQty; // no change if reached stock limit
    }
} else { // dec
    if ($currentQty > 1) {
        $newQty = $currentQty - 1;
    } else {
        // if qty would go below 1, delete the item from cart
        mysqli_query($con, "DELETE FROM carts WHERE id = '$cart_id' AND buyer_id = '$B_ID'");
        header("Location: Cart.php");
        exit;
    }
}

// update quantity
mysqli_query($con, "UPDATE carts SET qty = '$newQty' WHERE id = '$cart_id' AND buyer_id = '$B_ID'");

header("Location: Cart.php");
exit;
