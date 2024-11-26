<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "dbact";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind parameters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Check if the username, full name, or email already exists
    $check_sql = "SELECT * FROM client WHERE full_name = '$full_name' OR email = '$email' OR username = '$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Error: Full name, email, or username already exists.');
            window.location.href = 'signup.php'; // Redirect to the signup page
        </script>";
    } else {
        // Handle file upload
        $profile_picture = $_FILES['profile_picture'];
        $upload_dir = 'picture/';
        $upload_file = $upload_dir . basename($profile_picture['name']);
        
        if ($profile_picture['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
                // Insert query including username and password fields
                $sql = "INSERT INTO client (full_name, contact, address, email, username, password, profile_picture) VALUES ('$full_name', '$contact', '$address', '$email', '$username', '$password', '$upload_file')";

                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    echo "<script>
                        alert('New record created successfully');
                        window.location.href = 'contact_info.php?client_id=" . $last_id . "'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Error: " . $conn->error . "');
                        window.location.href = 'signup.php'; 
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Error uploading file.');
                    window.location.href = 'signup.php'; 
                </script>";
            }
        } else {
            echo "<script>
                alert('File upload error: " . $profile_picture['error'] . "');
                window.location.href = 'signup.php'; 
            </script>";
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Signup Form</title>
    <link rel="stylesheet" href="styles.css">
    <script src="sweetalert2/dist/sweetalert2.all.min.js"></script>
    <style>
    /* Reset default browser styles and set a futuristic font */
    body {
        margin: 0;
        font-family: 'Roboto', sans-serif;
        background-color: #F4D03F; /* Beige background */
        color: #2F1D0D; /* Dark brown text */
    }

    h2 {
        text-align: center;
        color: #4E3B31; /* Gold for headings */
        margin-top: 30px;
        text-shadow: 0 0 8px rgba(0, 229, 255, 0.6); /* Glow effect */
    }

    /* Center the form on the page */
    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #4E3B31; /* Dark brown for form background */
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 229, 255, 0.2); /* Neon shadow */
    }

    /* Style for input fields, textarea, and buttons */
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="file"],
    textarea,
    button {
        width: 95%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #333; /* Dark border */
        border-radius: 5px;
        font-size: 16px;
        background-color: #2C2C2C; /* Darker input background */
        color: #E0E0E0; /* Light text color */
        transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transitions */
    }

    /* Style for textarea */
    textarea {
        resize: vertical;
    }

    /* Style for the button */
    button {
        background-color: #F4D03F; /* Yellow gold for the button */
        color: #2F1D0D; /* Dark brown text */
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 229, 255, 0.3); /* Glow effect */
        transition: background-color 0.3s, box-shadow 0.3s ease-in-out;
    }

    /* Button hover effect */
    button:hover {
        background-color: #2F1D0D; /* Dark brown on hover */
        color: #F4D03F; /* Gold text on hover */
        box-shadow: 0 6px 12px rgba(0, 229, 255, 0.5); /* Enhanced glow on hover */
    }

    /* Label styling */
    label {
        font-weight: bold;
        color: #F4D03F; /* Gold for labels */
        display: block;
        margin-bottom: 5px;
    }

    /* Input focus effect */
    input:focus,
    textarea:focus {
        background-color: #1E1E1E; /* Darker background on focus */
        box-shadow: 0 0 8px rgba(0, 229, 255, 0.3); /* Light glow on focus */
        border-color: #F4D03F; /* Gold border on focus */
    }
    </style>
</head>
<body>
    <h2>Client Signup Form</h2>
    <form action="signup.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div>
            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" required></textarea>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
        </div>
        <center><button type="submit">Sign Up</button></center>
        <center><a href="login.php">Login</a></center>
    </form>
</body>
</html>
