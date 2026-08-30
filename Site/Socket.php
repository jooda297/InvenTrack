<?php 
function notify_socket_server($productId, $newQuantity, $sellerId)
{
    $url = 'http://localhost:3000/product-updated';

    $payload = json_encode([
        'product_id'    => $productId,
        'new_quantity'  => $newQuantity,
        'seller_id'  => $sellerId,
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 3,
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        // log error if needed: curl_error($ch)
    }
    curl_close($ch);
}

function notify_order_socket($orderId, $status, $totalPrice, $createdAt, $sellerId)
{
    $url = 'http://localhost:3000/order-updated';

    $payload = json_encode([
        'order_id'    => $orderId,
        'status'      => $status,
        'total_price' => $totalPrice,
        'created_at'  => $createdAt,
        'seller_id'  => $sellerId,
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true
    ]);

    curl_exec($ch);
    curl_close($ch);
}