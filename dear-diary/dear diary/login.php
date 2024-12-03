<?php
session_start();

// Include database configuration
include("config.php");

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and validate user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $error = "Username should not be empty.";
    } elseif (empty($password)) {
        $error = "Password should not be empty.";
    } else {
        // Check if the username exists
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password matches - set session variables
                $_SESSION['user_id'] = $user['Id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['username'] = $user['username'];

                // Redirect to homepage
                header("Location: home.php");
                exit();
            } else {
                // Invalid password
                $error = "Incorrect password. Please try again.";
            }
        } else {
            // Username does not exist
            $error = "No account found with that username.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
<nav class="main-navigation">
      <ul class="nav-items"> 
        <li><a href="index.html" class="menu-item">ABOUT</a></li>
        <li><a href="index.html" class="menu-item">FEATURES</a></li>
        <li><a href="index.html" class="menu-item">REVIEWS</a></li>
        <li><a href="index.html" class="menu-item">DOWNLOAD</a></li>
      </ul>
    </nav>
  </header>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <!-- Display error message if exists -->
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                <div class="links">
                    Don't have an account? <a href="login_register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
