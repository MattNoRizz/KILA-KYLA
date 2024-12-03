<?php
require 'database_connection.php';
session_start(); // Ensure session is started

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('status' => false, 'msg' => 'Unauthorized access!'));
    exit;
}

$user_id = $_SESSION['user_id']; // Assume user_id is stored in session
$event_id = $_POST['event_id']; // Event ID passed from the frontend

// Check if the event belongs to the logged-in user
$check_event_query = "SELECT * FROM calendar_event_master WHERE event_id = '$event_id' AND user_id = '$user_id'";
$check_result = mysqli_query($con, $check_event_query);

if (mysqli_num_rows($check_result) > 0) {
    // Delete the event
    $delete_query = "DELETE FROM calendar_event_master WHERE event_id = '$event_id'";
    if (mysqli_query($con, $delete_query)) {
        $data = array(
            'status' => true,
            'msg' => 'Event deleted successfully!'
        );
    } else {
        $data = array(
            'status' => false,
            'msg' => 'Sorry, the event could not be deleted.'
        );
    }
} else {
    $data = array(
        'status' => false,
        'msg' => 'Event not found or unauthorized access.'
    );
}

echo json_encode($data);
?>
