<?php

header(
    'Content-Type: application/json'
);

require '../config/database.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$token = trim(
    $data['token'] ?? ''
);

$password = trim(
    $data['password'] ?? ''
);

if (
    empty($token) ||
    empty($password)
) {

    echo json_encode([
        'status' => false,
        'message' => 'Token and password are required'
    ]);

    exit;
}

$stmt = $conn->prepare(
    "SELECT *
     FROM password_resets
     WHERE token = ?
     AND expires_at > NOW()
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
        'message' => 'Invalid or expired token'
    ]);

    exit;
}

$reset = $result->fetch_assoc();

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "UPDATE users
     SET password = ?
     WHERE id = ?"
);

$stmt->bind_param(
    "si",
    $passwordHash,
    $reset['user_id']
);

$stmt->execute();

$stmt = $conn->prepare(
    "DELETE
     FROM password_resets
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $reset['id']
);

$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Password updated successfully'
]);