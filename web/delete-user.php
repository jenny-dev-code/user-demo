<?php

require '../includes/auth.php';
require '../config/database.php';

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
| Get User Photo
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT photo
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

$user = $result->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Delete Photo
|--------------------------------------------------------------------------
*/

if (!empty($user['photo'])) {

    $photoPath =
        '../uploads/users/' .
        $user['photo'];

    if (file_exists($photoPath)) {

        unlink($photoPath);
    }
}

/*
|--------------------------------------------------------------------------
| Delete User
|--------------------------------------------------------------------------
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