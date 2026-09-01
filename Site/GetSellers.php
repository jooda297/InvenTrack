<?php
session_start();
include "../Connect.php";

header('Content-Type: application/json; charset=utf-8');

$sellers = [];



/* Base query */
$sql = "SELECT id, name, description, image
        FROM users
        WHERE active = 1 AND user_type_id = 2";


/* Run query */
$result = mysqli_query($con, $sql);

if (!$result) {
    echo json_encode([
        'error' => mysqli_error($con)
    ]);
    exit;
}

/* Build response */
while ($row = mysqli_fetch_assoc($result)) {
    $sellers[] = $row;
}

/* Output clean JSON */
echo json_encode($sellers);

