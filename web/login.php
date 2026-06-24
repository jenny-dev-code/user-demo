<?php

session_start();

require '../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        $message = "Invalid Credentials";

    } else {

        $user = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $user['password']
            )
        ) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            header(
                "Location: dashboard.php"
            );
            exit;

        } else {

            $message = "Invalid Credentials";
        }
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
                        Login
                    </h3>

                    <?php if (!empty($message)) : ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary">
                                Login
                            </button>
                        </div>

                    </form>

                    <div class="text-center mt-3">

                        <a href="forgot-password.php">
                            Forgot Password?
                        </a>

                        <br>

                        <a href="register.php">
                            Create Account
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>