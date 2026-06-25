<?php

require '../includes/auth.php';
require '../config/database.php';

if ($_SESSION['role'] === 'admin') {

    $result = $conn->query(
        "SELECT *
         FROM users
         WHERE role != 'admin'
         ORDER BY id DESC"
    );

} else {

    $stmt = $conn->prepare(
        "SELECT *
         FROM users
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $_SESSION['user_id']
    );

    $stmt->execute();

    $result = $stmt->get_result();
}

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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <?php if ($_SESSION['role'] === 'admin') : ?>
                            
                                <th>Actions</th>
                            
                            <?php endif; ?>
                        </tr>

                    </thead>
                                
                    <tbody>

                        <?php while ($user = $result->fetch_assoc()) : ?>

                            <tr>

                                <td>
                                    <?php echo $user['id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['created_at']); ?>
                                </td>

                            <?php if ($_SESSION['role'] === 'admin') : ?>
                                <td>                                                        
                                    <a
                                        href="delete-user.php?id=<?php echo $user['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete user?')">
                                        Delete
                                    </a>                                                        
                                </td>                                                   
                            <?php endif; ?>

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