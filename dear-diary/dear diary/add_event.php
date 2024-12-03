<?php
include 'db.php';
session_start();

// Check if the user is logged in and user_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo 'User is not authenticated.';
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user_id

// Check if form data exists
if (isset($_POST['title'], $_POST['time_from'], $_POST['time_to'], $_POST['day'], $_POST['month'], $_POST['year'])) {
    
    // Sanitize and assign POST values to variables
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $time_from = filter_var($_POST['time_from'], FILTER_SANITIZE_STRING);
    $time_to = filter_var($_POST['time_to'], FILTER_SANITIZE_STRING);
    $day = filter_var($_POST['day'], FILTER_VALIDATE_INT);
    $month = filter_var($_POST['month'], FILTER_VALIDATE_INT);
    $year = filter_var($_POST['year'], FILTER_VALIDATE_INT);

    // Ensure that day, month, and year are valid
    if ($day && $month && $year) {
        try {
            // Prepare the SQL query including user_id
            $query = "INSERT INTO events (title, time_from, time_to, day, month, year, user_id) VALUES (:title, :time_from, :time_to, :day, :month, :year, :user_id)";
            $stmt = $pdo->prepare($query);
            
            // Execute the query with the provided values, including user_id
            $stmt->execute([
                'title' => $title,
                'time_from' => $time_from,
                'time_to' => $time_to,
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'user_id' => $user_id  // Add the user_id from the session
            ]);

            echo 'Event added successfully';

        } catch (PDOException $e) {
            // Catch any database-related errors
            echo 'Database error: ' . $e->getMessage();
        }
    } else {
        echo 'Invalid date information';
    }
} else {
    echo 'Missing form data';
}
?>
