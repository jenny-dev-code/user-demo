<?php

header('Content-Type: application/json');

require '../includes/api-auth.php';

if (!isset($authUser)) {
    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($userId <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid user id'
    ]);
    exit;
}

// Permission Check
if ($authUser['role'] == 'admin') {

    // Admin cannot delete own account
    if ($userId == $authUser['id']) {
        echo json_encode([
            'status' => false,
            'message' => 'You cannot delete your own account'
        ]);
        exit;
    }

} else {

    // Normal user can delete only own account
    if ($userId != $authUser['id']) {
        echo json_encode([
            'status' => false,
            'message' => 'Access denied'
        ]);
        exit;
    }
}

// Check user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        'status' => false,
        'message' => 'User not found'
    ]);
    exit;
}

// Delete uploaded record photos
$stmt = $conn->prepare("SELECT photo FROM records WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    if (!empty($row['photo'])) {

        $path = "../uploads/records/" . $row['photo'];

        if (file_exists($path)) {
            unlink($path);
        }
    }
}

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {

    echo json_encode([
        'status' => true,
        'message' => 'User deleted successfully'
    ]);

} else {

    echo json_encode([
        'status' => false,
        'message' => $stmt->error
    ]);
}