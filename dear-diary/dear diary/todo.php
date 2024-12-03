<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit;
}

include("config.php"); // Database connection

$user_id = $_SESSION['user_id'];
$firstName = $_SESSION['first_name'] ?? 'User';


// Fetch the to-do list for the logged-in user
$stmt = $con->prepare("SELECT id, todo_name, completed FROM todos WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$todos = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="todo.css">
  <link rel="stylesheet" href="sidebar.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <title>To-Do List</title>
</head>
<body>
  <div>
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

         

    <!-- Main Content Area (To-Do List) -->
<section class="home">
  <h2 class="text">To-Do List</h2>

  <!-- To-Do Form -->
  <form class="new-note" action="tnewtodo.php" method="post">
    <input type="text" name="todo_name" placeholder="Enter your to-do" required>
    <button>New To Do</button>
  </form>

  <!-- Container for To-Do List -->
  <div class="todo-container">
    <!-- Displaying To-Do List -->
    <?php foreach ($todos as $todo): ?>
      <div class="todo-item">
      <form style="display: inline" action="tchange_status.php" method="post">
                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                <input type="checkbox" name="status" value="1" <?php echo $todo['completed'] ? 'checked' : ''; ?> onchange="this.form.submit()">
            </form>
            <?php echo htmlspecialchars($todo['todo_name']); ?>
            <form style="display: inline" action="tdelete.php" method="post" onsubmit="return confirmDelete();">
                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                <button>Delete</button>
            </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this?");
    }
</script>
  <!-- Sidebar Toggle Script -->
  <script>
    const body = document.querySelector('body'),
          sidebar = body.querySelector('nav'),
          toggle = body.querySelector(".toggle");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });
  </script>
</body>
</html>
