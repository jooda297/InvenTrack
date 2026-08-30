<?php

include "../Connect.php";

$isActive = $_GET['isActive'];
$offer_id = $_GET['offer_id'];

$stmt = $con->prepare("UPDATE offers SET active = ? WHERE id = ? ");

$stmt->bind_param("ii", $isActive, $offer_id);

if ($stmt->execute()) {

    if ($isActive == 0) {

        echo "<script language='JavaScript'>
        alert ('Offer Has Been Deleted Successfully !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Offers.php';
        </script>";

    } else {
        echo "<script language='JavaScript'>
alert ('Offer Has Been Restored Successfully !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Offers.php';
</script>";
    }

}
