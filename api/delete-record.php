<?php

header(
    'Content-Type: application/json'
);

require '../includes/api-auth.php';

$recordId = (int)($_GET['id'] ?? 0);

if ($recordId <= 0) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid record'
    ]);

    exit;
}

$stmt = $conn->prepare(
    "SELECT *
     FROM records
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $recordId
);

$stmt->execute();

$result = $stmt->get_result();

if (!$result->num_rows) {

    echo json_encode([
        'status' => false,
        'message' => 'Record not found'
    ]);

    exit;
}

$record = $result->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Permission Check
|--------------------------------------------------------------------------
*/

if (
    $authUser['role'] !== 'admin'
    &&
    $record['user_id'] != $authUser['id']
) {

    echo json_encode([
        'status' => false,
        'message' => 'Access denied'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Photo
|--------------------------------------------------------------------------
*/

if (!empty($record['photo'])) {

    $photoPath =
        '../uploads/records/' .
        $record['photo'];

    if (file_exists($photoPath)) {

        unlink($photoPath);
    }
}

/*
|--------------------------------------------------------------------------
| Delete Record
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "DELETE
     FROM records
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $recordId
);

$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Record deleted successfully'
]);