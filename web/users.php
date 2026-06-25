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

            <?php if (isset($_GET['success'])) : ?>
            
                <div class="alert alert-success" id="alertMessage">
            
                    <?php

                    switch ($_GET['success']) {
            
                        case 'deleted':
                            echo 'User deleted successfully.';
                            break;
                    }
                        
                    ?>

                </div>
                        
            <?php endif; ?>
                        
            <?php if (isset($_GET['error'])) : ?>
            
                <div class="alert alert-danger" id="alertMessage">
            
                    <?php

                    switch ($_GET['error']) {
            
                        case 'self_delete':
                            echo 'You cannot delete your own account.';
                            break;
                        
                        case 'not_found':
                            echo 'User not found.';
                            break;
                        
                        case 'invalid_user':
                            echo 'Invalid user selected.';
                            break;
                        
                        case 'delete_failed':
                            echo 'Failed to delete user.';
                            break;
                        
                        default:
                            echo 'Something went wrong.';
                    }
                        
                    ?>

                </div>
                        
            <?php endif; ?>

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
                            <th>Time</th>
                            <th>Actions</th>
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

                                <td>                           
                                    <a href="delete-user.php?id=<?php echo $user['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                        Delete
                                    </a>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

if (window.history.replaceState) {

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );
}

setTimeout(function () {

    const alert =
        document.getElementById(
            'alertMessage'
        );

    if (alert) {

        alert.remove();
    }

}, 3000);


</script>

</body>
</html>