<?php

include "../Connect.php";
$isActive = $_GET['isActive'];
$category_id = $_GET['category_id'];

$stmt = $con->prepare("UPDATE categories SET active = ? WHERE id = ? ");

$stmt->bind_param("ii", $isActive, $category_id);

if ($stmt->execute()) {

    if ($isActive == 0) {

        echo "<script language='JavaScript'>
        alert ('Category Has Been Deleted Successfully !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Categories.php';
        </script>";

    } else {
        echo "<script language='JavaScript'>
alert ('Category Has Been Restored Successfully !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Categories.php';
</script>";
    }

}
