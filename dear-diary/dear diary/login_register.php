<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
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

    <main class="signup-page">
        <div class="container">
            <div class="box form-box">
                <?php 
                include("config.php");

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Sanitize and validate input
                    $first_name = htmlspecialchars(trim($_POST['first_name']));
                    $last_name = htmlspecialchars(trim($_POST['last_name']));
                    $username = htmlspecialchars(trim($_POST['username']));
                    $password = htmlspecialchars(trim($_POST['password']));

                    if (empty($first_name) || empty($last_name) || empty($username) || empty($password)) {
                        echo "<div class='message'>
                                <p>All fields are required. Please fill in the form completely.</p>
                              </div>";
                    } else {
                        // Verify unique username
                        $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            echo "<div class='message'>
                                    <p>This username is already taken. Try another one!</p>
                                  </div>";
                            echo "<a href='javascript:history.back();'><button class='btn'>Go Back</button></a>";
                        } else {
                            // Hash the password
                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                            // Insert user into the database
                            $stmt = $con->prepare("INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssss", $first_name, $last_name, $username, $hashed_password);

                            if ($stmt->execute()) {
                                echo "<div class='message'>
                                        <p>Registration successful!</p>
                                      </div>";
                                echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
                            } else {
                                echo "<div class='message'>
                                        <p>An error occurred. Please try again later.</p>
                                      </div>";
                            }
                        }

                        $stmt->close();
                    }
                } else {
                ?>

                <h1>Welcome!</h1>
                <p>Create an account</p>
                <form action="" method="post">
                    <div class="field input">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autocomplete="off" required>
                    </div>
                    
                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                    </div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Register">
                    </div>
                    
                    <div class="links">
                        Already a member? <a href="login.php">Sign In</a>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>
