<?php

session_start();

include "../Connect.php";

header("Content-Type: application/json");

// 1) Products created per day
$sqlCreated = "
    SELECT DATE(created_at) AS day, COUNT(*) AS count
    FROM products
    GROUP BY DATE(created_at)
    ORDER BY day ASC
";
$resCreated = mysqli_query($con, $sqlCreated);

$days          = [];
$createdCounts = [];

while ($row = mysqli_fetch_assoc($resCreated)) {
    $days[]          = $row['day'];
    $createdCounts[] = (int) $row['count'];
}

// 2) Total quantity per day
$sqlQty = "
    SELECT DATE(created_at) AS day, SUM(qty) AS total_qty
    FROM products
    GROUP BY DATE(created_at)
    ORDER BY day ASC
";
$resQty = mysqli_query($con, $sqlQty);

$qtyTotals = [];
while ($row = mysqli_fetch_assoc($resQty)) {
    $qtyTotals[] = (int) $row['total_qty'];
}

// 3) Active vs inactive counts
$sqlActive = "
    SELECT
        SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) AS active_count,
        SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) AS inactive_count
    FROM products
";
$resActive  = mysqli_query($con, $sqlActive);
$activeData = mysqli_fetch_assoc($resActive);

echo json_encode([
    "days"          => $days,
    "createdCounts" => $createdCounts,
    "qtyTotals"     => $qtyTotals,
    "active"        => $activeData,
]);
