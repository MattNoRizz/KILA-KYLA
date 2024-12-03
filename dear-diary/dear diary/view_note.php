<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID
$firstName = $_SESSION['first_name'] ?? 'User';

/** @var Connection $connection */
$connection = require_once 'pdo.php';

// Get the note ID from the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $noteId = (int)$_GET['id'];

    // Fetch the note by ID and user ID
    $note = $connection->getNoteById($noteId, $userId);
} else {
    die("Invalid or missing note ID.");
}

if (!$note) {
    die("Note not found or you don't have access to this note.");
}

// Handle the note update when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedNote = [
        'title' => $_POST['title'],
        'description' => $_POST['description']
    ];

    // Update the note
    if ($connection->updateNote($noteId, $userId, $updatedNote)) {
        header("Location: view_note.php?id=" . $noteId); // Redirect after success
        exit();
    } else {
        $error = "Error updating note.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($note['title']); ?></title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="diary.css">
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
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.toggle-sidebar');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('close');
                document.body.classList.toggle('sidebar-collapsed');
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
        <main class="main-content">
            <?php if (isset($error)) : ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <!-- If it's in POST mode (after submitting the form) -->
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                <h1>Edit Note</h1>
                <form action="view_note.php?id=<?php echo $note['id']; ?>" method="POST">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($note['description']); ?></textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" class="button">Save Changes</button>
                        <a href="view_note.php?id=<?php echo $note['id']; ?>" class="button">Cancel</a>
                    </div>
                </form>
            <?php else : ?>
                <!-- Display Note Details -->
                <h1><?php echo htmlspecialchars($note['title']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($note['description'])); ?></p>
                <small>Created on: <?php echo date('d M Y, H:i', strtotime($note['create_date'])); ?></small>
                <div class="actions">
                    <a href="diary.php" class="button">Back to Notes</a>
                    <!-- Edit Button (will trigger GET method) -->
                    <a href="view_note.php?id=<?php echo $note['id']; ?>" class="button">Edit</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
