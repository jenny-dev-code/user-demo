<?php

header(
    'Content-Type: application/json'
);

require '../includes/api-auth.php';

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

if ($authUser['role'] !== 'admin') {

    echo json_encode([
        'status' => false,
        'message' => 'Access denied'
    ]);

    exit;
}

$userId = (int)($_GET['id'] ?? 0);

if ($userId <= 0) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid user'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Prevent Self Delete
|--------------------------------------------------------------------------
*/

if ($userId == $authUser['id']) {

    echo json_encode([
        'status' => false,
        'message' => 'You cannot delete your own account'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Check User Exists
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id
     FROM users
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result = $stmt->get_result();

if (!$result->num_rows) {

    echo json_encode([
        'status' => false,
        'message' => 'User not found'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Record Photos
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT photo
     FROM records
     WHERE user_id = ?"
);

$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result = $stmt->get_result();

while ($record = $result->fetch_assoc()) {

    if (!empty($record['photo'])) {

        $photoPath =
            '../uploads/records/' .
            $record['photo'];

        if (file_exists($photoPath)) {

            unlink($photoPath);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Delete User
|--------------------------------------------------------------------------
|
| records table should have:
| ON DELETE CASCADE
|
*/

$stmt = $conn->prepare(
    "DELETE
     FROM users
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $userId
);

if (!$stmt->execute()) {

    echo json_encode([
        'status' => false,
        'message' => 'Failed to delete user'
    ]);

    exit;
}

echo json_encode([
    'status' => true,
    'message' => 'User deleted successfully'
]);