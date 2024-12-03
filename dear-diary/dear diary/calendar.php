<?php
session_start(); // Start session to get user_id
header('Content-Type: application/json'); // Return JSON response

// Include database connection
include 'db.php';

$response = [];

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not authenticated'
    ]);
    exit;
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID
$firstName = $_SESSION['first_name'] ?? 'User';


// Handle POST request for adding an event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate received data
    if (!isset($data['day'], $data['month'], $data['year'], $data['title'], $data['time_from'], $data['time_to'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields'
        ]);
        exit;
    }

    // Clean the data to prevent SQL injection
    $day = filter_var($data['day'], FILTER_VALIDATE_INT);
    $month = filter_var($data['month'], FILTER_VALIDATE_INT);
    $year = filter_var($data['year'], FILTER_VALIDATE_INT);
    $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $time_from = filter_var($data['time_from'], FILTER_SANITIZE_STRING);
    $time_to = filter_var($data['time_to'], FILTER_SANITIZE_STRING);

    // Prepare and execute the SQL query
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO events (day, month, year, title, time_from, time_to, user_id) 
             VALUES (:day, :month, :year, :title, :time_from, :time_to, :user_id)"
        );

        $stmt->execute([
            ':day' => $day,
            ':month' => $month,
            ':year' => $year,
            ':title' => $title,
            ':time_from' => $time_from,
            ':time_to' => $time_to,
            ':user_id' => $user_id
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Event added successfully'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
// Handle GET request to fetch events
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'events' => $events
        ];
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }

    echo json_encode($response);
} else {
    // Invalid request method
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>
