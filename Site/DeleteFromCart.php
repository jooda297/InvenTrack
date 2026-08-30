<?php

include "../Connect.php";
$cart_id = $_GET['cart_id'];

$stmt = $con->prepare("DELETE FROM carts WHERE id = ? ");

$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {

    echo "<script language='JavaScript'>
alert ('Item Has Been Deleted Successfully !');
</script>";

    echo "<script language='JavaScript'>
document.location='./Cart.php';
</script>";

}
