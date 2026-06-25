<?php

session_start();

header(
    'Content-Type: application/json'
);

require '../config/database.php';
require '../includes/api-auth.php';


if ($authUser['role'] === 'admin') {

    $userId = (int)($_POST['user_id'] ?? 0);

    if ($userId <= 0) {

        echo json_encode([
            'status' => false,
            'message' => 'User ID is required'
        ]);

        exit;
    }

    $stmt = $conn->prepare(
            "SELECT id
             FROM users
             WHERE id = ?
             AND role = 'user'"
            );

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    if (!$stmt->get_result()->num_rows) {

        echo json_encode([
            'status' => false,
            'message' => 'Invalid user selected'
        ]);

        exit;
    }

} else {

    $userId = $authUser['id'];
}

$description =
    trim($_POST['description'] ?? '');

$location =
    trim($_POST['location'] ?? '');

if (
    empty($description) ||
    empty($location)
) {

    echo json_encode([
        'status' => false,
        'message' => 'Description and location are required'
    ]);

    exit;
}

$photo = null;

if (
    isset($_FILES['photo']) &&
    $_FILES['photo']['error'] === 0
) {

    if (!is_dir('../uploads/records')) {

        mkdir(
            '../uploads/records',
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
        '../uploads/records/' . $filename
    );

    $photo = $filename;
}

$stmt = $conn->prepare(
    "INSERT INTO records
    (
        user_id,
        description,
        location,
        photo
    )
    VALUES
    (
        ?,?,?,?
    )"
);

$stmt->bind_param(
    "isss",
    $userId,
    $description,
    $location,
    $photo
);

if (!$stmt->execute()) {

    echo json_encode([
        'status' => false,
        'message' => 'Failed to save record'
    ]);

    exit;
}

echo json_encode([
    'status' => true,
    'message' => 'Record added successfully'
]);