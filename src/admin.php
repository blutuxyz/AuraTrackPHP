<?php
include("conf.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
    $password_attempt = $_POST['admin_password'];

    // Verify the password
    if ($password_attempt === $admin_password) {
        // Password is correct, process the form
        $mysqli = new mysqli($database_address, $database_username, $database_password, $database_name);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if (
            isset($_POST['tracking_number']) &&
            isset($_POST['progress_percentage']) &&
            isset($_POST['new_status']) &&
            isset($_POST['timestamp'])
        ) {
            $tracking_number = $_POST['tracking_number'];
            $progress_percentage = $_POST['progress_percentage'];
            $new_status = $_POST['new_status'];
            $timestamp = $_POST['timestamp'];

            // Insert the new status update
            $insert_query = "INSERT INTO tracking_history (tracking_number, status, progress_percentage, timestamp) VALUES ('$tracking_number', '$new_status', $progress_percentage, '$timestamp')";
            $result = $mysqli->query($insert_query);

            if ($result) {
                echo "Status update added successfully!";
            } else {
                echo "Error adding status update: " . $mysqli->error;
            }
        } else {
            echo "Incomplete form data!";
        }

        $mysqli->close();
    } else {
        echo "Invalid password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>
    <div class="max-w-md mx-auto">
        <!-- Admin Login Form -->
        <?php if (!isset($_POST['admin_password']) || $_POST['admin_password'] !== $admin_password) : ?>
            <div class="mt-4 text-center">
                <h3>Admin Login:</h3>
                <h4>Please login to the <?php echo $auratrack_company; ?> admin panel, powered by AuraTrack</h4>
                <form action="admin.php" method="post">
                    <label for="admin_password">Enter Admin Password:</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        <?php endif; ?>

        <!-- Admin Status Update Form -->
        <?php if (isset($_POST['admin_password']) && $_POST['admin_password'] === $admin_password) : ?>
            <div class="mt-4 text-center">
                <h3>Add Status Update:</h3>
                <form action="admin.php" method="post">
                    <label for="tracking_number">Tracking Number:</label>
                    <input type="text" id="tracking_number" name="tracking_number" required><br>

                    <label for="progress_percentage">Progress Percentage:</label>
                    <input type="number" id="progress_percentage" name="progress_percentage" min="0" max="100" required><br>

                    <label for="new_status">New Status:</label>
                    <input type="text" id="new_status" name="new_status" required><br>

                    <label for="timestamp">Timestamp:</label>
                    <input type="datetime-local" id="timestamp" name="timestamp" required><br>

                    <input type="hidden" name="admin_password" value="<?php echo $admin_password; ?>">
                    <input type="submit" value="Add Update">
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
