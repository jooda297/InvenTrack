<?php
session_start();
include "../Connect.php";

header('Content-Type: application/json; charset=utf-8');

$sellers = [];

/* SAFE GETS — no warnings */
$filter = $_GET['filter'] ?? null;

/* Base query */
$sql = "SELECT id, name, total_rate, description, image
        FROM users
        WHERE active = 1 AND user_type_id = 2";

/* Sorting */
if ($filter === 'popularity') {
    $sql .= " ORDER BY total_rate DESC";
}

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

