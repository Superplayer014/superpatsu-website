<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch client details based on client_id from the URL
$client_id = intval($_GET['client_id']); 
$sql_client = "SELECT * FROM client WHERE client_id = ?";
$stmt = $conn->prepare($sql_client);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result_client = $stmt->get_result();

if (!$result_client || $result_client->num_rows === 0) {
    die("Client not found.");
}

$client = $result_client->fetch_assoc();

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Validate file type and size
    if (in_array($file['type'], $allowed_types) && $file['error'] == 0 && $file['size'] <= 5000000) {
        // Define unique file name
        $upload_dir = 'uploads/';
        $file_name = $upload_dir . time() . "_" . basename($file['name']);
        
        // Move file to directory
        if (move_uploaded_file($file['tmp_name'], $file_name)) {
            // Update profile picture path in the database using a prepared statement
            $sql_update = "UPDATE client SET profile_picture = ? WHERE client_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $file_name, $client_id);
            if ($stmt_update->execute()) {
                echo "<script>alert('Profile picture uploaded successfully.');</script>";
            } else {
                echo "<script>alert('Error updating database.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the file.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type, size too large, or upload error.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Information</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(to right, #5A3F37, #8A6E5A); /* Gradient from soft brown to beige */
        color: #E0E0E0; /* Light text color */
        margin: 0;
        padding: 0;
    }

    header {
        background: linear-gradient(to right, #8A6E5A, #B09A72); /* Gradient from light brown to beige */
        color: #121212; /* Dark text color */
        padding: 20px;
        text-align: center;
        font-size: 28px;
        box-shadow: 0 4px 10px rgba(138, 110, 90, 0.3); /* Soft shadow */
    }

    .content {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #F4D03F; /* Beige background */
        border-radius: 10px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2); /* Shadow for content */
        position: relative;
        color: #121212; /* Dark text */
    }

    .profile-picture {
        max-width: 150px;
        border-radius: 50%;
        position: absolute;
        top: 20px;
        right: 20px;
        box-shadow: 0 4px 10px rgba(138, 110, 90, 0.4); /* Glowing effect for profile picture */
    }

    label {
        color: #F4D03F; /* Gold color for labels */
        font-size: 16px;
    }

    input[type="file"], input[type="submit"] {
        background-color: #3A2A1D; /* Dark brown background for inputs */
        color: #F4D03F; /* Gold text */
        border: 1px solid #4E3B31; /* Darker brown border */
        border-radius: 5px;
        padding: 8px 12px;
        width: 100%;
        margin-top: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Smooth hover effect */
    }

    input[type="file"]:hover, input[type="submit"]:hover {
        background-color: #B09A72; /* Hover effect in beige */
    }

    .back-button {
        display: inline-block;
        padding: 12px 18px;
        background-color: #F4D03F; /* Yellow gold for buttons */
        color: #121212; /* Dark text */
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 10px rgba(138, 110, 90, 0.4); /* Soft shadow for buttons */
    }

    .back-button:hover {
        background-color: #3A2A1D; /* Dark brown on hover */
        box-shadow: 0 6px 15px rgba(138, 110, 90, 0.6); /* Enhanced shadow on hover */
    }

</style>
</head>
<body>

    <header>Contact Information</header>

    <div class="content">
        <?php if (!empty($client['profile_picture'])): ?>
            <img class="profile-picture" src="<?= htmlspecialchars($client['profile_picture']) ?>" alt="Profile Picture">
        <?php endif; ?>
        
        <h2>Client Details</h2>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($client['full_name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($client['contact']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($client['username']) ?></p>
        <p><strong>Password:</strong> <em>********</em></p>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label><br>
            <input type="file" name="profile_picture" id="profile_picture" required><br><br>
            <input type="submit" value="Upload">
        </form>
        <br>
        
        <a class="back-button" href="edit_client.php?client_id=<?= $client_id ?>">Edit Information</a>
        <a class="back-button" href="product_dashboard.php?client_id=<?= $client_id ?>">Back to Dashboard</a>
    </div>

</body>
</html>
