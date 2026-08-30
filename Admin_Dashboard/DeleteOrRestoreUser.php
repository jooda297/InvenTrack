<?php

include "../Connect.php";

$isActive = $_GET['isActive'];
$user_id = $_GET['user_id'];

$stmt = $con->prepare("UPDATE users SET active = ? WHERE id = ? ");

$stmt->bind_param("ii", $isActive, $user_id);

if ($stmt->execute()) {

    if ($isActive == 0) {

        echo "<script language='JavaScript'>
        alert ('User Has Been Deleted Successfully !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Users.php';
        </script>";

    } else {
        echo "<script language='JavaScript'>
alert ('User Has Been Restored Successfully !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Users.php';
</script>";
    }

}
