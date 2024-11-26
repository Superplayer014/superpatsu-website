<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch client details based on client_id from the URL
$client_id = $_GET['client_id']; 
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

if (!$result_client) {
    die("Query failed: " . $conn->error);
}

$client = $result_client->fetch_assoc();

if (!$client) {
    die("Client not found.");
}

// Update client information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $sql_update = "UPDATE client SET full_name = '$full_name', contact = '$contact', address = '$address' WHERE client_id = $client_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Client information updated successfully.');</script>";
        header("Location: contact_info.php?client_id=$client_id"); // Redirect after successful update
        exit;
    } else {
        echo "<script>alert('Error updating client information: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Client Information</title>
    <style>
    /* Global Styling */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #121212; /* Dark background */
        color: #E0E0E0; /* Light text for contrast */
        margin: 0;
        padding: 0;
    }

    /* Header Styling */
    header {
        background-color: #121212; /* Dark background for header */
        color: #00E5FF; /* Neon blue text */
        padding: 30px; /* Padding around the header */
        text-align: center; /* Center the text */
        font-size: 28px; /* Larger font size */
        font-weight: bold;
        text-shadow: 0 0 10px rgba(0, 229, 255, 0.7); /* Glowing text */
        box-shadow: 0 6px 15px rgba(0, 229, 255, 0.2); /* Neon glow shadow */
    }

    /* Content Styling */
    .content {
        max-width: 650px; /* Max width for the content */
        margin: 40px auto; /* Center the content */
        padding: 25px; /* Padding inside the content area */
        border: 1px solid #333; /* Dark border for sleek look */
        border-radius: 10px; /* Rounded corners */
        background-color: #1E1E1E; /* Dark background */
        box-shadow: 0 4px 12px rgba(0, 229, 255, 0.3); /* Neon glow shadow */
        position: relative; /* Position for absolute child elements */
    }

    /* Form Input Fields */
    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #333;
        border-radius: 8px;
        background-color: #222;
        color: #E0E0E0;
        font-size: 16px;
        box-sizing: border-box; /* Ensure padding does not exceed input width */
        transition: all 0.3s ease;
    }

    input[type="text"]:focus {
        border-color: #00E5FF;
        background-color: #333;
        outline: none;
    }

    /* Button Styling */
    input[type="submit"] {
        padding: 12px 18px;
        background-color: #00E5FF; /* Neon blue background */
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        box-shadow: 0 3px 6px rgba(0, 229, 255, 0.2); /* Glow effect */
        transition: background-color 0.3s, transform 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #00BBD4; /* Darker blue on hover */
        transform: translateY(-2px); /* Slight lift effect */
        box-shadow: 0 4px 10px rgba(0, 229, 255, 0.4); /* Enhanced glow */
    }

    /* Link styling */
    a {
        color: #00E5FF; /* Neon blue text for links */
        text-decoration: none;
        font-size: 16px;
        display: inline-block;
        margin-top: 20px;
        transition: color 0.3s;
    }

    a:hover {
        color: #00BBD4; /* Hover effect on links */
    }

    /* Footer Styling */
    footer {
        background-color: #1E1E1E; /* Dark footer background */
        color: #E0E0E0; /* Light grey text */
        text-align: center; /* Centered text */
        padding: 15px; /* Padding around the footer */
        position: relative; /* Positioning for footer */
        bottom: 0; /* Stick to the bottom */
        width: 100%; /* Full width */
        box-shadow: 0 -4px 10px rgba(0, 229, 255, 0.3); /* Neon shadow on top */
    }
</style>

</head>
<body>

    <header>
        Edit Client Information
    </header>

    <div class="content">
        <h2>Update Information for <?= htmlspecialchars($client['full_name']) ?></h2>
        <form method="POST">
            <label for="full_name">Full Name:</label><br>
            <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" required><br>

            <label for="contact">Contact:</label><br>
            <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($client['contact']) ?>" required><br>

            <label for="address">Address:</label><br>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($client['address']) ?>" required><br>

            <input type="submit" value="Update Information">
        </form>

        <a href="contact_info.php?client_id=<?= $client_id ?>">Cancel</a>
    </div>
</body>
</html>
