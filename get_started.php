<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started Button</title>
    <style>
        /* Styling the "Get Started" button */
        .get-started-button {
            display: inline-block;
            padding: 15px 40px;
            font-size: 20px;
            font-weight: bold;
            color: #121212;
            text-transform: uppercase;
            text-align: center;
            background: linear-gradient(to right, #8A6E5A, #B09A72);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(138, 110, 90, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }

        .get-started-button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(255, 204, 0, 0.8);
            background: linear-gradient(to right, #B09A72, #8A6E5A);
        }

        .get-started-button:active {
            transform: scale(0.98);
            box-shadow: 0 4px 8px rgba(138, 110, 90, 0.8);
        }

        /* Center content for demonstration */
        body {
            background: linear-gradient(to right, #5A3F37, #8A6E5A);
            color: #E0E0E0;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .selection-buttons {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .selection-buttons button {
            padding: 10px 30px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #4E3B31;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .selection-buttons button:hover {
            background-color: #8A6E5A;
        }
    </style>
</head>
<body>
    <!-- Get Started Button -->
    <div id="main-content">
        <button class="get-started-button" id="getStarted">Get Started</button>
    </div>

    <!-- Admin or Client Buttons -->
    <div id="selection" class="selection-buttons">
        <button id="adminButton">I am an Admin</button>
        <button id="clientButton">I am a Client</button>
    </div>

    <script>
        // Select elements
        const getStartedButton = document.getElementById('getStarted');
        const mainContent = document.getElementById('main-content');
        const selection = document.getElementById('selection');
        const adminButton = document.getElementById('adminButton');
        const clientButton = document.getElementById('clientButton');

        // Show admin/client options on button click
        getStartedButton.addEventListener('click', () => {
            mainContent.style.display = 'none'; // Hide "Get Started" button
            selection.style.display = 'flex';  // Show admin/client buttons
        });

        // Redirect to Admin page
        adminButton.addEventListener('click', () => {
            alert("Redirecting to Admin page...");
            // Replace the following line with your admin page URL
            window.location.href = 'admin_login.php';
        });

        // Redirect to Client page
        clientButton.addEventListener('click', () => {
            alert("Redirecting to Client page...");
            // Replace the following line with your client page URL
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
