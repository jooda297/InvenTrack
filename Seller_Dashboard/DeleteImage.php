<?php

include "../Connect.php";
$image_id   = $_GET['image_id'];
$product_id = $_GET['product_id'];

$stmt = $con->prepare("DELETE FROM product_images WHERE id = ?");

$stmt->bind_param("i", $image_id);

if ($stmt->execute()) {

    echo "<script language='JavaScript'>
        alert ('Image Has Been Deleted Successfully !');
        </script>";

    echo "<script language='JavaScript'>
        document.location='./Product-Images.php?product_id={$product_id}';
        </script>";

}
