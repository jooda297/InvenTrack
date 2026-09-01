<?php

session_start();
include "../Connect.php";

header('Content-Type: application/json; charset=utf-8');

$response = [];

$B_ID = $_SESSION['B_ID'] ?? null;

if (!$B_ID) {
    echo json_encode([
        'error' => true,
        'message' => 'No User'
    ]);
    exit;
}

$product_id = (int)($_GET['product_id'] ?? 0);
$qty = (int)($_GET['qty'] ?? 1);

$optionsRaw = $_GET['options'] ?? '{}';
$optionsArray = json_decode($optionsRaw, true);

if (!is_array($optionsArray)) {
    $optionsArray = [];
}

if ($product_id <= 0 || $qty <= 0) {
    echo json_encode([
        'error' => true,
        'message' => 'Invalid product or quantity'
    ]);
    exit;
}


// Get product customization status
$productStmt = $con->prepare(
    "SELECT is_customized
     FROM products
     WHERE id = ?
     LIMIT 1"
);

$productStmt->bind_param("i", $product_id);
$productStmt->execute();

$productResult = $productStmt->get_result();
$product = $productResult->fetch_assoc();

if (!$product) {
    echo json_encode([
        'error' => true,
        'message' => 'Product not found'
    ]);
    exit;
}

$isCustomized = (int)$product['is_customized'];


// Normalize options
$color_id = $optionsArray['color_id'] ?? '';
$size_id  = $optionsArray['size_id'] ?? '';

$normalizedOptions = json_encode([
    'color_id' => (string)$color_id,
    'size_id' => (string)$size_id
]);


// Get existing rows for this buyer + product
$cartStmt = $con->prepare(
    "SELECT id, qty, options
     FROM carts
     WHERE buyer_id = ?
     AND product_id = ?"
);

$cartStmt->bind_param(
    "ii",
    $B_ID,
    $product_id
);

$cartStmt->execute();

$result = $cartStmt->get_result();

$matchingRows = [];

while ($row = $result->fetch_assoc()) {

    // Non-custom product:
    // all rows for this product should be treated as the same item
    if (!$isCustomized) {

        $matchingRows[] = $row;
        continue;
    }


    // Customized product:
    // compare color + size
    $existingOptions = json_decode($row['options'], true);

    if (!is_array($existingOptions)) {
        $existingOptions = [];
    }

    $existingColor = $existingOptions['color_id'] ?? '';
    $existingSize  = $existingOptions['size_id'] ?? '';

    if (
        (string)$existingColor === (string)$color_id &&
        (string)$existingSize === (string)$size_id
    ) {
        $matchingRows[] = $row;
    }
}


// If matching cart rows already exist
if (count($matchingRows) > 0) {

    $mainCartId = (int)$matchingRows[0]['id'];

    $newQty = $qty;

    // Add quantities from ALL duplicate rows
    foreach ($matchingRows as $row) {
        $newQty += (int)$row['qty'];
    }


    // Update first row
    $updateStmt = $con->prepare(
        "UPDATE carts
         SET qty = ?, options = ?
         WHERE id = ?"
    );

    $updateStmt->bind_param(
        "isi",
        $newQty,
        $normalizedOptions,
        $mainCartId
    );

    $updateStmt->execute();


    // Delete duplicate rows
    for ($i = 1; $i < count($matchingRows); $i++) {

        $duplicateId = (int)$matchingRows[$i]['id'];

        $deleteStmt = $con->prepare(
            "DELETE FROM carts
             WHERE id = ?"
        );

        $deleteStmt->bind_param(
            "i",
            $duplicateId
        );

        $deleteStmt->execute();
    }

} else {

    // Product not already in cart
    $insertStmt = $con->prepare(
        "INSERT INTO carts
        (buyer_id, product_id, options, qty)
        VALUES (?, ?, ?, ?)"
    );

    $insertStmt->bind_param(
        "iisi",
        $B_ID,
        $product_id,
        $normalizedOptions,
        $qty
    );

    $insertStmt->execute();
}


// Count cart rows
$countStmt = $con->prepare(
    "SELECT COUNT(id)
     FROM carts
     WHERE buyer_id = ?"
);

$countStmt->bind_param(
    "i",
    $B_ID
);

$countStmt->execute();

$countStmt->bind_result($cart_count);
$countStmt->fetch();


echo json_encode([
    'error' => false,
    'cart_count' => $cart_count
]);

exit;
?>