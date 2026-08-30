<?php
session_start();

include "../Connect.php";

$categories = [];

$category_id = $_GET['category_id'];

$sql = "SELECT id, name FROM sub_categories WHERE category_id = '$category_id' AND active = 1";

$sql1 = mysqli_query($con, $sql);

while ($row1 = mysqli_fetch_array($sql1)) {

    $subCategoryId = $row1['id'];

    $sql2 = mysqli_query($con, "SELECT COUNT(id) AS products_count from products WHERE active = 1 AND sub_category_id = '$subCategoryId'");
    $row2 = mysqli_fetch_array($sql2);

    $products_count = $row2['products_count'];

    $categories[] = [
        'id'    => $row1['id'],
        'name'  => $row1['name'],
        'count' => $products_count,
    ];
}

echo json_encode($categories);
