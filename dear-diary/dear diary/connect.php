<?php

// Retrieve and sanitize input
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Debugging: Output the sanitized input values
var_dump($username); // This will show the raw value of $username

// Trim spaces and check for empty input
$username = trim($username);

// Validate input
if (empty($first_name)) {
    die("First name should not be empty.");
}
if (empty($last_name)) {
    die("Last name should not be empty.");
}
if (empty($username)) {
    die("Username should not be empty.");
}
if (empty($password)) {
    die("Password should not be empty.");
}

// Database connection details
$host = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "diary_app";

// Create connection using PDO instead of MySQLi for better security and flexibility
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error reporting
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the username already exists in the database
$stmt = $conn->prepare("SELECT Id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() >0) {
    die("Username already exists, please choose a different one.");
}


$password = $_POST['password'];  // The password entered by the user
// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert data into the database using prepared statement
$sql = "INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$first_name, $last_name, $username, $hashed_password])) {
    // Redirect to signin.html on successful registration
    header("Location: signin.html");
    exit();
} else {
    echo "Error: " . $stmt->errorInfo()[2];  // More informative error message
}

?>
