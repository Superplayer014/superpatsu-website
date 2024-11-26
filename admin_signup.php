<?php
// Include your database connection file
include 'db.php'; // Ensure the path to your db.php is correct

// Initialize message variable
$message = '';
$message_type = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If email exists, show an error message
        $message = "Email is already taken.";
        $message_type = 'error-message';
    } else {
        // Hash the password
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // Insert the new admin data into the database
        $sql = "INSERT INTO admin (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $admin_name, $admin_email, $hashed_password);
        if ($stmt->execute()) {
            // If insertion is successful, show a success message
            $message = "Admin account created successfully. You can now log in.";
            $message_type = 'success-message';
        } else {
            // If there is an error while inserting
            $message = "Error creating account. Please try again.";
            $message_type = 'error-message';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <style>
    /* Basic styling for the sign-up form */
    body {
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #121212; /* Dark background */
    }

    .signup-container {
        background-color: #1E1E1E; /* Dark form background */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 229, 255, 0.3); /* Neon blue glow */
        max-width: 400px;
        width: 100%;
        border: 1px solid #333; /* Subtle border */
    }

    .signup-container h2 {
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

    .success-message {
        color: green;
        text-align: center;
        margin: 10px 0;
        font-size: 16px;
    }
</style>

</head>
<body>
    <div class="signup-container">
        <h2>Admin Sign Up</h2>
        <?php if (isset($message)): ?>
            <p class="<?= $message_type ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="admin_signup.php" method="POST">
            <div class="input-group">
                <label for="admin_name">Name</label>
                <input type="text" name="admin_name" id="admin_name" required>
            </div>
            <div class="input-group">
                <label for="admin_email">Email</label>
                <input type="email" name="admin_email" id="admin_email" required>
            </div>
            <div class="input-group">
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" required>
            </div>
            <div class="input-group">
                <center><input type="submit" value="Sign Up"></center><br>
            <center><a href="admin_login.php">Login</a></center>

            </div>
        </form>
    </div>
</body>
</html>
