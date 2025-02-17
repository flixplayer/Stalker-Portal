<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stalker Portal Login</title>
    <style>
        @keyframes neon {
            0% {
                text-shadow: 0 0 5px #39ff14, 0 0 10px #39ff14, 0 0 15px #39ff14, 0 0 20px #39ff14, 0 0 25px #39ff14, 0 0 30px #39ff14, 0 0 35px #39ff14;
            }
            25% {
                text-shadow: 0 0 5px #ff014f, 0 0 10px #ff014f, 0 0 15px #ff014f, 0 0 20px #ff014f, 0 0 25px #ff014f, 0 0 30px #ff014f, 0 0 35px #ff014f;
            }
            50% {
                text-shadow: 0 0 5px #14fffd, 0 0 10px #14fffd, 0 0 15px #14fffd, 0 0 20px #14fffd, 0 0 25px #14fffd, 0 0 30px #14fffd, 0 0 35px #14fffd;
            }
            75% {
                text-shadow: 0 0 5px #fdd614, 0 0 10px #fdd614, 0 0 15px #fdd614, 0 0 20px #fdd614, 0 0 25px #fdd614, 0 0 30px #fdd614, 0 0 35px #fdd614;
            }
            100% {
                text-shadow: 0 0 5px #39ff14, 0 0 10px #39ff14, 0 0 15px #39ff14, 0 0 20px #39ff14, 0 0 25px #39ff14, 0 0 30px #39ff14, 0 0 35px #39ff14;
            }
        }
        body {
            background-color: #e0f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h1 {
            animation: neon 3s infinite;
        }
        .notification {
            color: green;
            font-weight: bold;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .form-container, .iframe-container {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
            margin: 10px;
        }
        form {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button-container button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        iframe {
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .iframe-wrapper {
            margin-bottom: 20px;
        }
    </style>
    <script>
        function generateM3UPlaylist(event) {
            event.preventDefault();
            const iframe = document.getElementById('m3uIframe');
            iframe.src = "process.php?m3u=1";
        }

        function viewProfile() {
            const iframe = document.getElementById('resultIframe');
            iframe.src = "process.php?profile=true";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <center><h1>Stalker Portal Login</h1></center>
            <form action="process.php" method="POST">
                <label for="file">PLAYLIST NAME:</label>
                <input type="text" id="file" placeholder="Enter Name For M3U Playlist Without .m3u" autocomplete="off" name="file" required>
                
                <label for="logoUrl">LOGO URL:</label>
                <input type="text" id="logoUrl" placeholder="Enter Own Logo For Channels" autocomplete="off" name="logoUrl">
                
                <label for="domain">PORTAL URL:</label>
                <input type="text" id="domain" placeholder="EX: smart4k.cc" autocomplete="off" name="domain" required>
                
                <label for="mac">MAC ADDRESS:</label>
                <input type="text" id="mac" placeholder="00:1a:79:00:00:00" autocomplete="off" name="mac" required>
                
                <label for="d1">DEVICE ID 1:</label>
                <input type="text" id="d1" placeholder="Enter Device ID 1" autocomplete="off" name="d1" required>
                
                <label for="d2">DEVICE ID 2:</label>
                <input type="text" id="d2" placeholder="Enter Device ID 2" autocomplete="off" name="d2" required>
                
                <label for="sn">SERIAL NUMBER:</label>
                <input type="text" id="sn" placeholder="Enter Serial Number" autocomplete="off" name="sn" required>
                
                <label for="model">STB TYPE:</label>
                <select id="model" placeholder="Select Mag Model" autocomplete="off" name="model" required>
                    <option value="">SELECT STB TYPE</option>
                    <option value="MAG250">MAG250</option>
                    <option value="MAG254">MAG254</option>
                    <option value="MAG270">MAG270</option>
                </select>
                
                <input type="submit" value="SUBMIT">
            </form>
        </div>
        <div class="iframe-container">
            <center><h1>Stalker Profile</h1></center>

            <?php
            if (isset($_GET['success']) && $_GET['success'] == "true") {
                echo '<p class="notification">Stalker data Fetched Successfully & Stored.</p>';
            }
            ?>
            <div class="button-container">
                <button onclick="viewProfile()">View Profile</button>
            </div>
            <div class="iframe-wrapper">
                <iframe id="resultIframe" src="" height="200px"></iframe>
                <iframe id="m3uIframe" src="" height="60px"></iframe>
                <form onsubmit="generateM3UPlaylist(event)">
                    <input type="hidden" name="m3u" value="1">
                    <input type="submit" value="Generate M3U Playlist">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
