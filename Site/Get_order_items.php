<?php
session_start();
include "../Connect.php";

header('Content-Type: application/json; charset=utf-8');

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

$response = [];

if ($order_id <= 0) {
    echo json_encode($response);
    exit;
}

$sql33 = mysqli_query($con, "SELECT product_id, seller_id, option_id, quantity, product_price FROM order_items WHERE order_id = '$order_id'");

while ($row33 = mysqli_fetch_assoc($sql33)) {

    $product_id    = (int) $row33['product_id'];
    $seller_id     = (int) $row33['seller_id'];
    $quantity      = (int) $row33['quantity'];
    $product_price = (float) $row33['product_price'];

    // defaults (so no undefined variable warnings)
    $color_value = '';
    $size_value  = '';

    // option_id may be NULL or empty or not JSON
    $options = [];
    if (!empty($row33['option_id'])) {
        $decoded = json_decode($row33['option_id'], true);
        if (is_array($decoded)) {
            $options = $decoded;
        }
    }

    $color_id = $options['color_id'] ?? '';
    $size_id  = $options['size_id'] ?? '';

    // product info
    $sql66 = mysqli_query($con, "SELECT name AS product_name, image AS product_image FROM products WHERE id = '$product_id' LIMIT 1");
    $row66 = mysqli_fetch_assoc($sql66);

    $product_name  = $row66['product_name'] ?? '';
    $product_image = $row66['product_image'] ?? '';

    // seller name
    $sql777 = mysqli_query($con, "SELECT name AS seller_name FROM users WHERE id = '$seller_id' LIMIT 1");
    $row777 = mysqli_fetch_assoc($sql777);

    $seller_name = $row777['seller_name'] ?? '';

    // option values
    if ($color_id !== '') {
        $color_id = (int) $color_id;
        $sqlColor = mysqli_query($con, "SELECT value FROM product_options WHERE id = '$color_id' LIMIT 1");
        $rowColor = mysqli_fetch_assoc($sqlColor);
        $color_value = $rowColor['value'] ?? '';
    }

    if ($size_id !== '') {
        $size_id = (int) $size_id;
        $sqlSize = mysqli_query($con, "SELECT value FROM product_options WHERE id = '$size_id' LIMIT 1");
        $rowSize = mysqli_fetch_assoc($sqlSize);
        $size_value = $rowSize['value'] ?? '';
    }

    $response[] = [
        "product_id"    => $product_id,
        "product_name"  => $product_name,
        "product_image" => $product_image,
        "color_value"   => $color_value,
        "size_value"    => $size_value,
        "price"         => $product_price,
        "qty"           => $quantity,
        "seller_name"   => $seller_name,
    ];
}

echo json_encode($response);
exit;
