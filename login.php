<?php
session_start();

// Define the correct username and password
$correct_username = 'admin';
$correct_password = 'password';

// Set the session timeout duration (10 minutes)
$timeout_duration = 600;

// Check if the session is set and if it has timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Last request was more than 10 minutes ago
    session_unset();     // Unset $_SESSION variable for the run-time
    session_destroy();   // Destroy session data in storage
    header('Location: login.php');
    exit;
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the credentials
    if ($username === $correct_username && $password === $correct_password) {
        // Store the username in the session
        $_SESSION['username'] = $username;
        // Redirect to the protected page
        header('Location: index.php');
        exit;
    } else {
        $error_message = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #e0f7fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: left;
        }
        form {
            max-width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #ffffff57;
            box-shadow: 0 0 10px rgb(0 0 0 / 69%);
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
           
        }
        input[type="text"], input[type="password"] {
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
            font-size: 14px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://vip-tv.online/publ-images/stalker-portal.png" alt="Logo" class="logo">
        <form action="login.php" method="POST">
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <label for="username">Username:</label>
            <input type="text" id="username" placeholder="Username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" placeholder="Password" name="password" required>
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>