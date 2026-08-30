<?php

include "../Connect.php";

$isActive = $_GET['isActive'];
$sub_category_id = $_GET['sub_category_id'];
$category_id = $_GET['category_id'];

$stmt = $con->prepare("UPDATE sub_categories SET active = ? WHERE id = ? ");

$stmt->bind_param("ii", $isActive, $sub_category_id);

if ($stmt->execute()) {

    if ($isActive == 0) {

        echo "<script language='JavaScript'>
        alert ('Category Has Been Deleted Successfully !');
        </script>";

        echo "<script language='JavaScript'>
        document.location='./Sub_Categories.php?category_id={$category_id}';
        </script>";

    } else {
        echo "<script language='JavaScript'>
alert ('Category Has Been Restored Successfully !');
</script>";

        echo "<script language='JavaScript'>
document.location='./Sub_Categories.php?category_id={$category_id}';
</script>";
    }

}
