<?php

require '../includes/api-auth.php';

if ($authUser['role'] === 'admin') {

    $result = $conn->query(
        "SELECT
            id,
            name,
            email,
            role,
            created_at
         FROM users
         ORDER BY id DESC"
    );

    $users = [];

    while ($row = $result->fetch_assoc()) {

        $users[] = $row;
    }

} else {

    $users[] = [

        'id' => $authUser['id'],

        'name' => $authUser['name'],

        'email' => $authUser['email'],

        'role' => $authUser['role'],

        'created_at' => $authUser['created_at']
    ];
}

echo json_encode([
    'status' => true,
    'data' => $users
]);