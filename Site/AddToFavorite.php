<?php
include "../Connect.php";
session_start();

$response = [
    "error"   => false,
    "action"  => null,   // "added" or "removed"
    "message" => ""
];

if (isset($_GET['buyer_id']) && isset($_GET['product_id'])) {

    $buyer_id   = (int) $_GET['buyer_id'];
    $product_id = (int) $_GET['product_id'];

    // Does this favorite already exist?
    $checkSql = "SELECT id FROM favorites WHERE buyer_id = ? AND product_id = ?";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param("ii", $buyer_id, $product_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult && $checkResult->num_rows > 0) {
        // 🔻 Already in favorites → REMOVE it
        $delSql = "DELETE FROM favorites WHERE buyer_id = ? AND product_id = ?";
        $delStmt = $con->prepare($delSql);
        $delStmt->bind_param("ii", $buyer_id, $product_id);

        if ($delStmt->execute()) {
            $response["error"]   = false;
            $response["action"]  = "removed";
            $response["message"] = "Removed from favorites";
        } else {
            $response["error"]   = true;
            $response["message"] = "Failed to remove from favorites";
        }

    } else {
        // 🔺 Not in favorites → ADD it
        $insSql = "INSERT INTO favorites (buyer_id, product_id) VALUES (?, ?)";
        $insStmt = $con->prepare($insSql);
        $insStmt->bind_param("ii", $buyer_id, $product_id);

        if ($insStmt->execute()) {
            $response["error"]   = false;
            $response["action"]  = "added";
            $response["message"] = "Added to favorites";
        } else {
            $response["error"]   = true;
            $response["message"] = "Failed to add to favorites";
        }
    }

} else {
    $response["error"]   = true;
    $response["message"] = "Missing buyer_id or product_id";
}

header("Content-Type: application/json");
echo json_encode($response);
