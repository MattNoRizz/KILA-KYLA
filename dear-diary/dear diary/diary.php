<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user session data
$userId = $_SESSION['user_id'];
$firstName = $_SESSION['first_name'] ?? 'User';

/** @var Connection $connection */
$connection = require_once 'pdo.php';

// Get the actual PDO instance
$pdo = $connection->getPdo();

// Fetch notes for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY create_date DESC");
$stmt->execute(['user_id' => $userId]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if an ID is passed for editing
$currentNote = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $noteId = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $noteId, 'user_id' => $userId]);
    $currentNote = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Diary</title>
    <link rel="stylesheet" href="diary.css">
    <link rel="stylesheet" href="sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700&display=swap" rel="stylesheet">
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
    <main class="main-content">
        <h1><?php echo isset($currentNote) ? 'Edit Note' : 'Write a New Note'; ?></h1>

        <!-- New or Edit Form -->
        <form class="new-note" action="dcreate.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($currentNote['id'] ?? ''); ?>">
            <input type="text" name="title" placeholder="Note Title" autocomplete="off" value="<?php echo htmlspecialchars($currentNote['title'] ?? ''); ?>">
            <textarea name="description" rows="5" placeholder="Note Description"><?php echo htmlspecialchars($currentNote['description'] ?? ''); ?></textarea>
            <button type="submit">
                <?php echo isset($currentNote['id']) ? 'Update Note' : 'Add Note'; ?>
            </button>
        </form>

        <h1>Your Notes</h1>
        <div class="notes">
            <?php foreach ($notes as $note): ?>
                <div class="note">
                    <div class="title">
                        <a href="view_note.php?id=<?php echo htmlspecialchars($note['id']); ?>">
                            <?php echo htmlspecialchars($note['title']); ?>
                        </a>
                    </div>
                    <div class="description">
                        <?php echo htmlspecialchars($note['description']); ?>
                    </div>
                    <small><?php echo date('d M Y, H:i', strtotime($note['create_date'])); ?></small>
                    <form action="ddelete.php" method="post" onsubmit="return confirmDelete();">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($note['id']); ?>">
                        <button type="submit" class="close">X</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this note?");
    }
</script>
</body>
</html>


