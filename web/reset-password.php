<?php

require '../config/database.php';

$token = $_GET['token'] ?? '';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password =
        trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT *
         FROM password_resets
         WHERE token = ?
         AND expires_at > NOW()
         LIMIT 1"
    );

    $stmt->bind_param(
        "s",
        $token
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows) {

        $reset =
            $result->fetch_assoc();

        $hash =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );

        $stmt = $conn->prepare(
            "UPDATE users
             SET password = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $hash,
            $reset['user_id']
        );

        $stmt->execute();

        $stmt = $conn->prepare(
            "DELETE
             FROM password_resets
             WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $reset['id']
        );

        $stmt->execute();

        $message =
            "Password Updated";
    }
}

?>

<?php include '../partials/header.php'; ?>

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Reset Password
                    </h3>

                    <?php if ($message) : ?>

                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <div class="d-grid">

                            <button class="btn btn-primary">
                                Update Password
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>