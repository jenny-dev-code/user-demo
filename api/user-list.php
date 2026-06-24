<?php

header(
    'Content-Type: application/json'
);

require '../config/database.php';

$result = $conn->query(
    "SELECT
        id,
        name,
        email,
        photo,
        description,
        location,
        created_at
     FROM users
     ORDER BY id DESC"
);

$users = [];

while ($row = $result->fetch_assoc()) {

    $users[] = $row;
}

echo json_encode([
    'status' => true,
    'data' => $users
]);