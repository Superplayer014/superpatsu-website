<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $client = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $client['password'])) {
            $_SESSION['client_id'] = $client['client_id'];
            $_SESSION['full_name'] = $client['full_name'];

            header("Location: product_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='login.php';</script>";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
    /* Reset some default browser styles */
    body {
        margin: 0;
        font-family: 'Arial', sans-serif;
        background-color: #F4D03F; /* Beige background */
        color: #2F1D0D; /* Dark brown text */
    }

    h2 {
        text-align: center;
        color: #4E3B31; /* Gold for headings */
        margin-top: 30px;
        font-size: 30px;
        text-shadow: 0 0 8px rgba(0, 229, 255, 0.8); /* Glowing text */
    }

    /* Center the form on the page */
    form {
        max-width: 400px; /* Set a maximum width for the form */
        margin: 0 auto; /* Center the form */
        padding: 25px;
        background-color: #4E3B31; /* Dark brown form background */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 6px 12px rgba(0, 229, 255, 0.3); /* Neon shadow */
    }

    /* Style for input fields, textarea, and buttons */
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="file"],
    textarea,
    button {
        width: 95%; /* Full width */
        padding: 12px; /* More padding */
        margin: 12px 0; /* Increased spacing between elements */
        border: 1px solid #333; /* Dark border */
        border-radius: 5px; /* Rounded corners */
        font-size: 16px; /* Larger text for inputs */
        background-color: #2C2C2C; /* Darker background for inputs */
        color: #E0E0E0; /* Light text */
        box-shadow: 0 2px 5px rgba(0, 229, 255, 0.2); /* Subtle glow */
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="email"]:focus,
    textarea:focus {
        border: 1px solid #F4D03F; /* Gold border on focus */
        background-color: #3A2A1D; /* Darker background */
    }

    /* Style for textarea */
    textarea {
        resize: vertical; /* Allow vertical resizing */
    }

    /* Style for the button */
    button {
        background-color: #F4D03F; /* Yellow gold */
        color: #2F1D0D; /* Dark brown text */
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
        padding: 15px;
        font-size: 18px;
    }

    /* Change background color on hover */
    button:hover {
        background-color: #2F1D0D; /* Dark brown on hover */
        color: #F4D03F; /* Gold text on hover */
        box-shadow: 0 4px 8px rgba(0, 229, 255, 0.4); /* Glow effect on hover */
    }

    /* Label styling */
    label {
        font-weight: bold; /* Make labels bold */
        display: block; /* Block display for labels */
        margin-bottom: 8px; /* Space between label and input */
        color: #F4D03F; /* Gold for labels */
    }
    </style>
</head>
<body>
    <h2>Client Login</h2>
    <form action="login.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <center><button type="submit">Login</button></center>
        <center><a href="signup.php">Signup</a></center>
    </form>
</body>
</html>
