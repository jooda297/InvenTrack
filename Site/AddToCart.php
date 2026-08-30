<?php

session_start();

include "../Connect.php";

$B_ID = $_SESSION['B_ID'];
// $B_ID = $_GET['b_id'];

if(!$B_ID) {


$response ['error'] = true;
$response ['message'] = "No User";

} else {

    
    $product_id = $_GET['product_id'];
    $options    = $_GET['options'];
    $qty        = $_GET['qty'];
    
    $response = [];
    
    $stmt = $con->prepare("INSERT INTO carts (buyer_id, product_id, options, qty) VALUES (?, ?, ?, ?) ");

    $stmt->bind_param("iisi", $B_ID, $product_id, $options, $qty);

// $stmt->execute();
//     print_r("error => " .$stmt->error);
// die;
    if ($stmt->execute()) {
    


        $sql211 = mysqli_query($con, "SELECT COUNT(id) AS cart_count FROM carts WHERE buyer_id = '$B_ID'");
        $row211 = mysqli_fetch_array($sql211);
    
        $cart_count = $row211['cart_count'];
    
        $response['error']      = false;
        $response['cart_count'] = $cart_count;
    
    } else {
                                print_r($product_id);

        $response['error']      = true;
        $response['cart_count'] = 0;
    }
    
    echo json_encode($response);
}
