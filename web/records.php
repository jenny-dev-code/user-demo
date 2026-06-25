<?php

require '../includes/auth.php';
require '../config/database.php';

if ($_SESSION['role'] === 'admin') {

    $query = "
        SELECT
            r.*,
            u.name,
            u.email
        FROM records r
        JOIN users u
        ON u.id = r.user_id
        ORDER BY r.id DESC
    ";

    $result =
        $conn->query($query);

} else {

    $stmt = $conn->prepare(
        "
        SELECT
            r.*,
            u.name,
            u.email
        FROM records r
        JOIN users u
        ON u.id = r.user_id
        WHERE r.user_id = ?
        ORDER BY r.id DESC
        "
    );

    $stmt->bind_param(
        "i",
        $_SESSION['user_id']
    );

    $stmt->execute();

    $result =
        $stmt->get_result();
}

include '../partials/header.php';
include '../partials/navbar.php';

?>

<div class="container mt-4">

    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">

                <h3>
                    Records List
                </h3>

            </div>
            <?php if (isset($_GET['success'])) : ?>
            
                <div
                    class="alert alert-success"
                    id="alertMessage"
                >
            
                    <?php

                    switch ($_GET['success']) {
            
                        case 'record_added':
                        
                            echo 'Record added successfully.';
                        
                            break;
                        
                        case 'deleted':
                        
                            echo 'Record deleted successfully.';
                        
                            break;
                        
                    }
                        
                    ?>

                </div>
                        
            <?php endif; ?>
            <?php if (isset($_GET['error'])) : ?>
            
                <div
                    class="alert alert-danger"
                    id="alertMessage"
                >
            
                    <?php

                    switch ($_GET['error']) {
            
                        case 'save_failed':
                        
                            echo 'Failed to save record.';
                        
                            break;
                        
                        default:
                        
                            echo 'Something went wrong.';
                    }
                        
                    ?>

                </div>
                        
            <?php endif; ?>
            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <?php if ($_SESSION['role'] === 'admin') : ?>

                                <th>User</th>

                            <?php endif; ?>

                            <th>Photo</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($record = $result->fetch_assoc()) : ?>

                            <tr>

                                <td>
                                    <?php echo $record['id']; ?>
                                </td>

                                <?php if ($_SESSION['role'] === 'admin') : ?>

                                    <td>

                                        <strong>
                                            <?php echo htmlspecialchars($record['name']); ?>
                                        </strong>

                                        <br>

                                        <small>
                                            <?php echo htmlspecialchars($record['email']); ?>
                                        </small>

                                    </td>

                                <?php endif; ?>

                                <td>

                                    <?php if (!empty($record['photo'])) : ?>

                                        <img
                                            src="../uploads/records/<?php echo htmlspecialchars($record['photo']); ?>"
                                            width="60"
                                            height="60"
                                            class="rounded"
                                        >

                                    <?php else : ?>

                                        No Photo

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($record['description']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($record['location']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($record['created_at']); ?>
                                </td>
                                <td>

                                    <a
                                        href="delete-record.php?id=<?php echo $record['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this record?')"
                                    >
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