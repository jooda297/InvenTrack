<?php

session_start();

include "../Connect.php";

$B_ID = $_SESSION['B_ID'];

$product_id = $_GET['product_id'];
$rate       = $_GET['Rate'];

$sql5 = mysqli_query($con, "SELECT * FROM product_rates WHERE product_id ='$product_id' AND buyer_id='$B_ID'");

if (mysqli_num_rows($sql5) > 0) {

    echo "<script language='JavaScript'>
    alert ('Sorry .. You Already Rate This Product Before !');
</script>";

    echo '<script language="JavaScript">
document.location="./Orders.php";
</script>';

} else {

    mysqli_query($con, "INSERT INTO product_rates (buyer_id, product_id, rate) values ('$B_ID','$product_id','$rate')");

    $sql1 = mysqli_query($con, "SELECT AVG(rate) AS new_avg_rate FROM product_rates WHERE product_id ='$product_id' ");
    $row1 = mysqli_fetch_array($sql1);

    $newAvgRate = $row1['new_avg_rate'];

    mysqli_query($con, "UPDATE products SET total_rate = '$newAvgRate' WHERE id = '$product_id'");

    echo "<script language='JavaScript'>
    alert ('Thank You For Your Rating Product !');
</script>";

    echo '<script language="JavaScript">
document.location="./Orders.php";
</script>';

}
