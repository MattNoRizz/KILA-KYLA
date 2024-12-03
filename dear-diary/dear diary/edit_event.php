<?php
require 'database_connection.php';

// Get data from the AJAX request
$event_id = $_POST['event_id'];
$event_name = $_POST['event_name'];
$event_start_date = date("Y-m-d", strtotime($_POST['event_start_date']));
$event_end_date = date("Y-m-d", strtotime($_POST['event_end_date']));

// Update query
$update_query = "UPDATE `calendar_event_master` 
                 SET `event_name` = '$event_name', 
                     `event_start_date` = '$event_start_date', 
                     `event_end_date` = '$event_end_date' 
                 WHERE `event_id` = $event_id";

if (mysqli_query($con, $update_query)) {
    $response = array(
        'status' => true,
        'msg' => 'Event updated successfully!'
    );
} else {
    $response = array(
        'status' => false,
        'msg' => 'Failed to update event. Please try again.'
    );
}

// Return response as JSON
echo json_encode($response);
?>
