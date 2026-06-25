<?php

require '../config/database.php';

$headers = getallheaders();

$token = $headers['Authorization'] ?? '';

$token = str_replace(
    'Bearer ',
    '',
    $token
);

if (empty($token)) {

    echo json_encode([
        'status' => false,
        'message' => 'Token required'
    ]);

    exit;
}

$stmt = $conn->prepare(
    "SELECT
        u.*
     FROM user_tokens ut
     JOIN users u
        ON u.id = ut.user_id
     WHERE ut.token = ?
     LIMIT 1"
);

$stmt->bind_param(
    "s",
    $token
);

$stmt->execute();

$result = $stmt->get_result();

if (!$result->num_rows) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid token'
    ]);

    exit;
}

$authUser =
    $result->fetch_assoc();