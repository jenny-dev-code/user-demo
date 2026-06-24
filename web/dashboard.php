<?php
    require '../includes/auth.php';
    include '../partials/header.php';
    include '../partials/navbar.php'; 
?>
<div class="container mt-4">

    <div class="card shadow">

        <div class="card-body">

            <h3>
                Welcome,
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </h3>

            <p class="text-muted">
                Manage your account and users.
            </p>

            <hr>

            <a
                href="users.php"
                class="btn btn-primary">
                View Users
            </a>

        </div>

    </div>

</div>

</body>
</html>