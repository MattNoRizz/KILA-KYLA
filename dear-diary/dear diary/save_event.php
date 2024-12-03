<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

// Sanitize input
$user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
$day = filter_var($data['day'], FILTER_SANITIZE_NUMBER_INT);
$month = filter_var($data['month'], FILTER_SANITIZE_NUMBER_INT);
$year = filter_var($data['year'], FILTER_SANITIZE_NUMBER_INT);
$title = filter_var($data['title'], FILTER_SANITIZE_STRING);
$time_from = filter_var($data['time_from'], FILTER_SANITIZE_STRING);
$time_to = filter_var($data['time_to'], FILTER_SANITIZE_STRING);

// Validate time format (adjust as needed)
if (!preg_match('/^\d{2}:\d{2}$/', $time_from) || !preg_match('/^\d{2}:\d{2}$/', $time_to)) {
    die(json_encode(['error' => 'Invalid time format']));
}

// Prepare and execute the SQL query
$stmt = $pdo->prepare("INSERT INTO events (user_id, day, month, year, title, time_from, time_to) VALUES (:user_id, :day, :month, :year, :title, :time_from, :time_to)");

try {
    $stmt->execute([
        ':user_id' => $user_id,
        ':day' => $day,
        ':month' => $month,
        ':year' => $year,
        ':title' => $title,
        ':time_from' => $time_from,
        ':time_to' => $time_to
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log($e->getMessage()); // Log the error for debugging
    echo json_encode(['error' => 'Database error']);
}