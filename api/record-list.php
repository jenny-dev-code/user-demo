<?php

header(
    'Content-Type: application/json'
);

require '../includes/api-auth.php';

/*
|--------------------------------------------------------------------------
| Admin -> All Records
|--------------------------------------------------------------------------
*/

if ($authUser['role'] === 'admin') {

    $stmt = $conn->prepare(
        "SELECT
            r.id,
            r.user_id,
            u.name,
            u.email,
            r.description,
            r.location,
            r.photo,
            r.created_at
         FROM records r
         INNER JOIN users u
            ON u.id = r.user_id
         ORDER BY r.id DESC"
    );

    $stmt->execute();

} else {

    /*
    |--------------------------------------------------------------------------
    | User -> Own Records Only
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "SELECT
            r.id,
            r.user_id,
            u.name,
            u.email,
            r.description,
            r.location,
            r.photo,
            r.created_at
         FROM records r
         INNER JOIN users u
            ON u.id = r.user_id
         WHERE r.user_id = ?
         ORDER BY r.id DESC"
    );

    $stmt->bind_param(
        "i",
        $authUser['id']
    );

    $stmt->execute();
}

$result = $stmt->get_result();

$records = [];

while ($row = $result->fetch_assoc()) {

    $records[] = [

        'id' => $row['id'],

        'user_id' => $row['user_id'],

        'name' => $row['name'],

        'email' => $row['email'],

        'description' => $row['description'],

        'location' => $row['location'],

        'photo' => $row['photo']
            ? 'http://localhost/user-demo/uploads/records/' . $row['photo']
            : null,

        'created_at' => $row['created_at']
    ];
}

echo json_encode([
    'status' => true,
    'data' => $records
]);