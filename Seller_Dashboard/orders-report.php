<?php
session_start();

include "../Connect.php";

header("Content-Type: application/json");

$S_ID = $_SESSION['S_Log'];

// Orders per day
$sql = "
    SELECT
        DATE(created_at) AS order_day,
        COUNT(*) AS order_count,
        SUM(product_price * quantity) AS total_revenue
    FROM order_items
    WHERE seller_id = '$S_ID'
    GROUP BY DATE(created_at)
    ORDER BY order_day ASC
";
$res = mysqli_query($con, $sql);

$days     = [];
$counts   = [];
$revenues = [];

while ($row = mysqli_fetch_assoc($res)) {
    $days[]     = $row['order_day'];
    $counts[]   = (int) $row['order_count'];
    $revenues[] = (float) $row['total_revenue'];
}

echo json_encode([
    "days"     => $days,
    "counts"   => $counts,
    "revenues" => $revenues,
]);
