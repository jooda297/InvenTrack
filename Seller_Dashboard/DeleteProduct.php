<?php
session_start();
include "../Connect.php";

$S_ID = $_SESSION['S_Log'] ?? null;
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if (!$S_ID || $product_id <= 0) {
    header("Location: ./Products.php");
    exit;
}

/*
  IMPORTANT:
  Only delete products that belong to the logged-in seller
  to prevent deleting other sellers' products.
*/

$con->begin_transaction();

try {
    // 1) Verify ownership
    $check = $con->prepare("SELECT id, image FROM products WHERE id=? AND seller_id=?");
    $check->bind_param("ii", $product_id, $S_ID);
    $check->execute();
    $product = $check->get_result()->fetch_assoc();

    if (!$product) {
        throw new Exception("Not found or not yours");
    }

    // 2) Delete dependent rows first (avoids FK constraint errors)
    $stmt = $con->prepare("DELETE FROM product_options WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $stmt = $con->prepare("DELETE FROM carts WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    // If you have order items table, do NOT delete usually (history),
    // but if your schema requires it, uncomment and adjust:
    // $stmt = $con->prepare("DELETE FROM order_items WHERE product_id=?");
    // $stmt->bind_param("i", $product_id);
    // $stmt->execute();

    // 3) Delete the product itself
    $del = $con->prepare("DELETE FROM products WHERE id=? AND seller_id=?");
    $del->bind_param("ii", $product_id, $S_ID);
    $del->execute();

    if ($del->affected_rows <= 0) {
        throw new Exception("Delete failed");
    }

    // 4) (Optional) delete image file from disk if you want
    if (!empty($product['image']) && file_exists(__DIR__ . '/' . $product['image'])) {
        @unlink(__DIR__ . '/' . $product['image']);
    }

    $con->commit();

    header("Location: ./Products.php?deleted=1");
    exit;

} catch (Exception $e) {
    $con->rollback();
    header("Location: ./Products.php?deleted=0");
    exit;
}
