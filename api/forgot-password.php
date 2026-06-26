<?php

header(
    'Content-Type: application/json'
);

require '../config/database.php';
require '../includes/mail.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$email = trim(
    $data['email'] ?? ''
);

if (empty($email)) {

    echo json_encode([
        'status' => false,
        'message' => 'Email is required'
    ]);

    exit;
}

$stmt = $conn->prepare(
    "SELECT id
     FROM users
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
    'status' => true,
    'message' => 'If the email exists, a reset link has been sent'
]);

    exit;
}

$user = $result->fetch_assoc();

$token = bin2hex(
    random_bytes(32)
);

$expiry = date(
    'Y-m-d H:i:s',
    strtotime('+1 hour')
);

$stmt = $conn->prepare(
    "INSERT INTO password_resets
    (
        user_id,
        token,
        expires_at
    )
    VALUES
    (
        ?,
        ?,
        ?
    )"
);

$stmt->bind_param(
    "iss",
    $user['id'],
    $token,
    $expiry
);

if (!$stmt->execute()) {

    echo json_encode([
        'status' => false,
        'message' => 'Failed to create reset token'
    ]);

    exit;
}

$link =
    "http://192.168.29.239/intruder_safety/web/reset-password.php?token="
    . $token;

$mailSent = sendResetMail(
    $email,
    $link
);

if (!$mailSent) {

    echo json_encode([
        'status' => false,
        'message' => 'Failed to send email'
    ]);

    exit;
}

echo json_encode([
    'status' => true,
    'message' => 'Password reset link sent successfully'
]);