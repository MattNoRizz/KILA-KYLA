<?php
include 'db.php';

$month = $_GET['month'];
$year = $_GET['year'];

// Check if month and year are set and valid
if (!isset($month) || !isset($year)) {
    echo json_encode(['error' => 'Month and year are required']);
    exit;
}

$query = "SELECT * FROM events WHERE month = :month AND year = :year";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute(['month' => $month, 'year' => $year]);

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($events) {
        echo json_encode($events);  // Return the events as JSON
    } else {
        echo json_encode(['message' => 'No events found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
