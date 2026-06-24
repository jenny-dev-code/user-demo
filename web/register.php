<?php

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $stmt = $conn->prepare(
        "INSERT INTO users(name,email,password)
        VALUES(?,?,?)"
    );

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $passwordHash
    );

    $stmt->execute();

    echo "User Registered";
}

?>
<?php include '../partials/header.php'; ?>

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Register
                    </h3>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required>
                        </div>

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

                            <button class="btn btn-success">
                                Register
                            </button>

                        </div>

                    </form>

                    <div class="text-center mt-3">

                        <a href="login.php">
                            Already have an account?
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
