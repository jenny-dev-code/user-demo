<?php

require '../includes/auth.php';
require '../config/database.php';

$result = $conn->query(
    "SELECT
        id,
        name,
        email,
        photo,
        description,
        location,
        created_at
     FROM users
     ORDER BY id DESC"
);

include '../partials/header.php';
include '../partials/navbar.php';
?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h3 class="mb-0">
                    Users List
                </h3>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Created At</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($user = $result->fetch_assoc()) : ?>

                            <tr>

                                <td>
                                    <?php echo $user['id']; ?>
                                </td>

                                <td>

                                    <?php if (!empty($user['photo'])) : ?>

                                        <img
                                            src="../uploads/users/<?php echo htmlspecialchars($user['photo']); ?>"
                                            alt="User Photo"
                                            width="60"
                                            height="60"
                                            class="rounded"
                                        >

                                    <?php else : ?>

                                        <span class="text-muted">
                                            No Photo
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['description']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['location']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['created_at']); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>