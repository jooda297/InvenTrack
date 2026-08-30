<?php

session_start();

include "../Connect.php";

$B_ID = $_SESSION['B_ID'];

$seller_id  = $_GET['seller_id'];
$rate       = $_GET['Rate'];

$sql5 = mysqli_query($con, "SELECT * FROM seller_rate WHERE seller_id ='$seller_id' AND buyer_id='$B_ID'");

if (mysqli_num_rows($sql5) > 0) {

    echo "<script language='JavaScript'>
    alert ('Sorry .. You Already Rate This Seller Before !');
</script>";

    echo '<script language="JavaScript">
document.location="./Orders.php";
</script>';

} else {

    mysqli_query($con, "INSERT INTO seller_rate (buyer_id, seller_id, rate) values ('$B_ID','$seller_id','$rate')");

    $sql1 = mysqli_query($con, "SELECT AVG(rate) AS new_avg_rate FROM seller_rate WHERE seller_id ='$seller_id' ");
    $row1 = mysqli_fetch_array($sql1);

    $newAvgRate = $row1['new_avg_rate'];

    mysqli_query($con, "UPDATE users SET total_rate = '$newAvgRate' WHERE id = '$seller_id'");

    echo "<script language='JavaScript'>
    alert ('Thank You For Your Rating Seller !');
</script>";

    echo '<script language="JavaScript">
document.location="./Orders.php";
</script>';

}
