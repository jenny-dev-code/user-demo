<?php

header(
    'Content-Type: application/json'
);

require '../config/database.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$email = trim(
    $data['email'] ?? ''
);

$password = trim(
    $data['password'] ?? ''
);

$stmt = $conn->prepare(
    "SELECT * FROM users
     WHERE email = ?"
);

$stmt->bind_param(
    "s",
    $email
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid Credentials"
    ]);

    exit;
}

$user = $result->fetch_assoc();

if (
    !password_verify(
        $password,
        $user['password']
    )
) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid Credentials"
    ]);

    exit;
}

$token = bin2hex(
    random_bytes(32)
);

$stmt = $conn->prepare(
    "INSERT INTO user_tokens
    (user_id, token)
    VALUES (?, ?)"
);

$stmt->bind_param(
    "is",
    $user['id'],
    $token
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "token" => $token,
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email']
    ]
]);