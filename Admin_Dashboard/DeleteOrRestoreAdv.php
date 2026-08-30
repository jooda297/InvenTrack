<?php

include "../Connect.php";
$isActive = $_GET['isActive'];
$adv_id = $_GET['adv_id'];

$stmt = $con->prepare("UPDATE advertisements SET active = ? WHERE id = ? ");

$stmt->bind_param("ii", $isActive, $adv_id);

if ($stmt->execute()) {

    if ($isActive == 0) {

        echo "<script language='JavaScript'>
        alert ('Adv Has Been Deleted Successfully !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Advertisements.php';
        </script>";

    } else {
        echo "<script language='JavaScript'>
alert ('Adv Has Been Restored Successfully !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Advertisements.php';
</script>";
    }

}
