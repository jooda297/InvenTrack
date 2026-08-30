<?php

include "../Connect.php";

$status      = 4;
$order_id = $_GET['order_id'];

$stmt = $con->prepare("UPDATE orders SET status_id = ? WHERE id = ? ");

$stmt->bind_param("ii", $status, $order_id);

if ($stmt->execute()) {

    echo "<script language='JavaScript'>
alert ('Order Has Been Canceled !');
</script>";

    echo "<script language='JavaScript'>
document.location='./Orders.php';
</script>";

}
