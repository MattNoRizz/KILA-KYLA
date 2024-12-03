<?php
// Start the session to manage user state
session_start();


// Turn on error reporting for debugging (optional, remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve and sanitize input
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));


// Debugging: Check the values of $username and $password
var_dump($username);
var_dump($password);

// Validate input
if (empty($username)) {
    die("Username should not be empty.");
}
if (empty($password)) {
    die("Password should not be empty.");
}

/** @var Connection $connection */
$connection = require_once 'pdo.php';

// Access the actual PDO instance
$pdo = $connection->getPdo();

// Query to check user credentials
$sql = "SELECT Id, password FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);

// Bind the username and execute the query
$stmt->execute(['username' => $username]);

// Check if the username exists
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $hashed_password = $user['password'];
    $user_id = $user['Id'];

    // Debugging: Print the hashed password and the entered password (remove in production)
     echo "Hashed password from DB: " . $hashed_password . "<br>";
     echo "Entered password: " . $password . "<br>";

    // Verify the password using password_verify
    if (password_verify($password, $hashed_password)) {
        // Store user information in the session
        $_SESSION['user_id'] = $user_id;

        // Redirect to home.html
        header("Location: home.php");
        exit();
    } else {
        // Invalid password
        echo "Password verification failed!<br>";
        echo "<div class='message'>
                    <p>Wrong Password</p>
                </div> <br>";
        echo "<a href='signin.html'><button class='btn'>Go Back</button></a>";
                    
    }
} else {
    // Username not found
    echo "No user found with that username.<br>";
    echo "<div class='message'>
                <p>Wrong Username</p>
            </div> <br>";
    echo "<a href='signin.html'><button class='btn'>Go Back</button></a>";
                
}

?>
