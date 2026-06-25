<?php

require '../includes/auth.php';
require '../config/database.php';

$recordId = (int)($_GET['id'] ?? 0);

if ($recordId <= 0) {

    header(
        'Location: records.php?error=invalid_record'
    );

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

    header(
        'Location: records.php?error=not_found'
    );

    exit;
}

$record = $result->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Permission Check
|--------------------------------------------------------------------------
*/

if (
    $_SESSION['role'] !== 'admin'
    &&
    $record['user_id'] != $_SESSION['user_id']
) {

    header(
        'Location: records.php?error=access_denied'
    );

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

header(
    'Location: records.php?success=deleted'
);

exit;