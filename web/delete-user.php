<?php

require '../includes/auth.php';
require '../config/database.php';

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

if ($_SESSION['role'] !== 'admin') {

    header(
        'Location: dashboard.php'
    );

    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {

    header(
        'Location: users.php?error=invalid_user'
    );

    exit;
}

if ($id == $_SESSION['user_id']) {

    header(
        'Location: users.php?error=self_delete'
    );

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
    $id
);

$stmt->execute();

$result = $stmt->get_result();

if (!$result->num_rows) {

    header(
        'Location: users.php?error=not_found'
    );

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
    $id
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
| Records will automatically delete because of:
| FOREIGN KEY (user_id)
| REFERENCES users(id)
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
    $id
);

if (!$stmt->execute()) {

    header(
        'Location: users.php?error=delete_failed'
    );

    exit;
}

header(
    'Location: users.php?success=deleted'
);

exit;