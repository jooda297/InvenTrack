<?php

include "../Connect.php";


$status_id   = $_GET['status_id'];
$order_id = $_GET['order_id'];

$stmt = $con->prepare("UPDATE orders SET status_id = ? WHERE id = ? ");

$stmt->bind_param("ii", $status_id, $order_id);

if ($stmt->execute()) {

    if ($status_id == 2) {

        echo "<script language='JavaScript'>
        alert ('Order is out for delivery !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Orders.php';
        </script>";

    } else if ($status_id == 3) {
        echo "<script language='JavaScript'>
alert ('Order Delivered !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Orders.php';
</script>";
    }

}
