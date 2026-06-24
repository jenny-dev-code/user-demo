<?php

header('Content-Type: application/json');

require '../config/database.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$description = trim($data['description'] ?? '');
$location = trim($data['location'] ?? '');
$photo = trim($data['photo'] ?? '');

if (
    empty($name) ||
    empty($email) ||
    empty($password)
) {

    echo json_encode([
        'status' => false,
        'message' => 'Required fields missing'
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

if ($result->num_rows > 0) {

    echo json_encode([
        'status' => false,
        'message' => 'Email already exists'
    ]);

    exit;
}

$photo = null;

if (
    isset($_FILES['photo']) &&
    $_FILES['photo']['error'] === 0
) {

    if (!is_dir('../uploads/users')) {

        mkdir(
            '../uploads/users',
            0777,
            true
        );
    }

    $filename =
        time() .
        '_' .
        basename(
            $_FILES['photo']['name']
        );

    move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        '../uploads/users/' . $filename
    );

    $photo = $filename;
}

$passwordHash =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

$stmt = $conn->prepare(
    "INSERT INTO users
    (
        name,
        email,
        password,
        photo,
        description,
        location
    )
    VALUES
    (
        ?,?,?,?,?,?
    )"
);

$stmt->bind_param(
    "ssssss",
    $name,
    $email,
    $passwordHash,
    $photo,
    $description,
    $location
);

if (!$stmt->execute()) {

    echo json_encode([
        'status' => false,
        'message' => 'Failed to create user'
    ]);

    exit;
}

echo json_encode([
    'status' => true,
    'message' => 'User created successfully'
]);