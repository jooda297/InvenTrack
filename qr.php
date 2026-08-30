<?php
session_start();
include "Connect.php";

if(!isset($_GET['pid']) || !is_numeric($_GET['pid'])){
  die("Invalid QR");
}
$product_id = (int)$_GET['pid'];

/* ✅ base url auto (works for localhost and IP) */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST']; // localhost OR 192.168.1.xx
$base   = $scheme.'://'.$host.'/Inventrack/';

// check product exists
$stmt = $con->prepare("SELECT id FROM products WHERE id = ? AND active = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
  die("Product not found");
}

// optional scan counter (if you still have column)
$con->query("UPDATE products SET qr_scans = qr_scans + 1 WHERE id = $product_id");

// route
if (isset($_SESSION['S_Log'])) {
  header("Location: {$base}Seller_Dashboard/Product_QR.php?product_id=$product_id");
  exit;
}

if (isset($_SESSION['B_ID'])) {
  header("Location: {$base}Site/Product.php?product_id=$product_id");
  exit;
}

// not logged in
$next = urlencode("qr.php?pid=$product_id");
header("Location: {$base}Login.php?next=$next");
exit;
