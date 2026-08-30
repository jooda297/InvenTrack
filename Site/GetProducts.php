<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include "../Connect.php";
$buyer_id = (isset($_GET['buyer_id']) && is_numeric($_GET['buyer_id'])) ? (int)$_GET['buyer_id'] : null;
$category_id     = (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) ? (int)$_GET['category_id'] : null;
$sub_category_id = (isset($_GET['sub_category_id']) && is_numeric($_GET['sub_category_id'])) ? (int)$_GET['sub_category_id'] : null;
$product_name    = (isset($_GET['product_name']) && strlen(trim($_GET['product_name']))) ? trim($_GET['product_name']) : null;
$filter          = (isset($_GET['filter']) && strlen(trim($_GET['filter']))) ? trim($_GET['filter']) : null;
$rating          = (isset($_GET['rating']) && is_numeric($_GET['rating'])) ? (float)$_GET['rating'] : null;
$min             = (isset($_GET['min']) && is_numeric($_GET['min'])) ? (float)$_GET['min'] : null;
$max             = (isset($_GET['max']) && is_numeric($_GET['max'])) ? (float)$_GET['max'] : null;

$sql = "
SELECT
    p.id,
    p.name,
    p.price,
    p.description,
    p.image,
    p.out_of_stock,
    c.name AS category_name,
    IF(f.id IS NULL, 0, 1) AS is_favorite
FROM products p
INNER JOIN categories c ON c.id = p.category_id
LEFT JOIN favorites f 
  ON f.product_id = p.id 
 AND f.buyer_id = ?
 AND f.active = 1
";


$conditions = ["p.active = 1"];
$params = [];
$types  = "";
$params[] = $buyer_id ?? 0;
$types .= "i";

// build WHERE + types
if ($category_id !== null)     { $conditions[] = "p.category_id = ?";     $params[] = $category_id;     $types .= "i"; }
if ($sub_category_id !== null) { $conditions[] = "p.sub_category_id = ?"; $params[] = $sub_category_id; $types .= "i"; }
if ($product_name !== null)    { $conditions[] = "p.name LIKE ?";         $params[] = "%{$product_name}%"; $types .= "s"; }
if ($rating !== null)          { $conditions[] = "p.total_rate = ?";      $params[] = $rating;          $types .= "d"; }
if ($min !== null)             { $conditions[] = "p.price >= ?";          $params[] = $min;             $types .= "d"; }
if ($max !== null)             { $conditions[] = "p.price <= ?";          $params[] = $max;             $types .= "d"; }

$orderBy = "";
if ($filter === 'popularity')  $orderBy = " ORDER BY p.total_rate DESC";
elseif ($filter === 'price')   $orderBy = " ORDER BY p.price DESC";

$sql .= " WHERE " . implode(" AND ", $conditions) . $orderBy;

$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed", "details" => mysqli_error($con)]);
    exit;
}

if (!empty($params)) {
    // bind_param needs references
    $bind = [];
    $bind[] = $types;
    for ($i = 0; $i < count($params); $i++) {
        $bind[] = &$params[$i];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
exit;
