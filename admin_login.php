<?php
// Include your database connection file
include 'db.php'; // Ensure the path to your db.php is correct

// Initialize error message variable
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Prepare the SQL query to fetch the admin based on email
    $sql = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email); // Bind the email to the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        // Fetch the admin data
        $admin = $result->fetch_assoc();

        // Verify the password (ensure you have hashed passwords in your database)
        if (password_verify($admin_password, $admin['admin_password'])) {
            // Set the session or cookie for the logged-in admin
            session_start();
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            // Redirect to the dashboard or admin area
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
    /* Basic styling for the login form */
    body {
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #121212; /* Dark background */
    }

    .login-container {
        background-color: #1E1E1E; /* Dark form background */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 229, 255, 0.3); /* Neon blue glow */
        max-width: 400px;
        width: 100%;
        border: 1px solid #333; /* Subtle border */
    }

    .login-container h2 {
        text-align: center;
        color: #00E5FF; /* Neon blue text */
        font-size: 28px;
        text-shadow: 0 0 8px rgba(0, 229, 255, 0.7); /* Glowing text */
    }

    .input-group {
        margin: 15px 0;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        color: #E0E0E0; /* Light text for labels */
        font-weight: bold;
    }

    .input-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #333;
        border-radius: 5px;
        background-color: #2C2C2C; /* Darker input background */
        color: #E0E0E0; /* Light text */
        font-size: 16px;
        box-shadow: 0 2px 5px rgba(0, 229, 255, 0.2); /* Glowing effect */
    }

    .input-group input[type="submit"] {
        background-color: #00E5FF; /* Neon blue */
        color: white;
        border: none;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transition */
    }

    .input-group input[type="submit"]:hover {
        background-color: #00BBD4; /* Darker blue on hover */
        box-shadow: 0 4px 10px rgba(0, 229, 255, 0.5); /* Glow effect */
    }

    .error-message {
        color: red;
        text-align: center;
        margin: 10px 0;
        font-size: 16px;
    }
</style>

</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <div class="input-group">
                <label for="admin_email">Email</label>
                <input type="email" name="admin_email" id="admin_email" required>
            </div>
            <div class="input-group">
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" required>
            </div>
            <div class="input-group">
                <center><input type="submit" value="Login"></center>
            </div>
            <center><a href="admin_signup.php">Signup</a></center>
        </form>
    </div>
</body>
</html>
