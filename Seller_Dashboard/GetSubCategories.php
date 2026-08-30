<?php

session_start();
require '../Connect.php';

$category_id = $_GET['category_id'];
$categories  = [];

$sql1 = mysqli_query($con, "SELECT id, name from sub_categories WHERE active = 1 AND category_id = '$category_id'");

while ($row1 = mysqli_fetch_array($sql1)) {

    $categories[] = $row1;

}

echo json_encode($categories);
