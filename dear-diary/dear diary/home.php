<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$firstName = $_SESSION['first_name'] ?? 'User'; // Fallback to 'User' if first name is not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Dear Diary</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="container">


        <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Hello, <?php echo htmlspecialchars($firstName); ?>!</h2>
            <button class="toggle-sidebar" onclick="toggleSidebar()">&#9776;</button>
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="diary.php">Start Writing Diary</a></li>
                <li><a href="todo.php">To-Do List</a></li>
                <li><a href="moodboard.php">Moodboard</a></li>
                <li><a href="dynamic_full_calendar.html">Calendar</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-button" onclick="confirmLogout()">Logout</a>
    </aside>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    // Get the sidebar element and the toggle button
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('.toggle-sidebar');

    // Toggle sidebar open/close
    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('close'); // Toggle the 'close' class on the sidebar
        document.body.classList.toggle('sidebar-collapsed'); // Adjust the body margin when sidebar is collapsed
    });
});
</script>
<script>
    function confirmLogout() {
        event.preventDefault();
        if (confirm("Are you sure you want to log out?")) {
            // Redirect to logout.php if the user confirms
            window.location.href = "logout.php";
        }
    }
</script>



        <!-- Main Content -->
        <main class="content">
            <h1>Welcome to Your Homepage</h1>
            <div class="features">
                <div class="feature-box">
                    <a href="diary.php">
                        <h2>Start Writing Diary</h2>
                        <p>Write down your thoughts and experiences.</p>
                    </a>
                </div>
                <div class="feature-box">
                    <a href="todo.php">
                        <h2>To-Do Lists</h2>
                        <p>Manage your tasks and stay organized.</p>
                    </a>
                </div>
                <div class="feature-box">
                    <a href="moodboard.php">
                        <h2>Moodboard</h2>
                        <p>Collect inspiration and express yourself.</p>
                    </a>
                </div>
                <div class="feature-box">
                    <a href="dynamic_full_calendar.html">
                        <h2>Calendar</h2>
                        <p>Keep track of important dates and events.</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
