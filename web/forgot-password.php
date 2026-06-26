<?php

require '../config/database.php';
require '../includes/mail.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    $stmt = $conn->prepare(
        "SELECT id
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param(
        "s",
        $email
    );

    $stmt->execute();

    $result = $stmt->get_result();
    // die($result->num_rows);
    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        $token = bin2hex(
            random_bytes(32)
        );

        $expiry =
            date(
                'Y-m-d H:i:s',
                strtotime('+1 hour')
            );

        $stmt = $conn->prepare(
            "INSERT INTO password_resets
            (user_id, token, expires_at)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "iss",
            $user['id'],
            $token,
            $expiry
        );

        $stmt->execute();

        $link =
            "http://192.168.29.239/intruder_safety/web/reset-password.php?token="
            . $token;

        if (sendResetMail($email, $link)) {
            $message = "Mail sent successfully";
        } else {
            $message = "Mail sending failed";
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

                    <h3 class="text-center mb-3">
                        Forgot Password
                    </h3>

                    <p class="text-muted text-center mb-4">
                        Enter your registered email address to receive a password reset link.
                    </p>

                    <?php if (!empty($message)) : ?>

                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required
                            >

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Send Reset Link
                            </button>

                        </div>

                    </form>

                    <div class="text-center mt-4">

                        <a
                            href="login.php"
                            class="text-decoration-none"
                        >
                            Back to Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
