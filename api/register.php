<?php

header('Content-Type: application/json');

require '../config/database.php';

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];

$hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "INSERT INTO users
    (name,email,password)
    VALUES(?,?,?)"
);

$stmt->bind_param(
    "sss",
    $name,
    $email,
    $hash
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Registered"
]);