<?php                
require 'database_connection.php'; 
session_start(); // Ensure session is started

$user_id = $_SESSION['user_id']; // Assume user_id is stored in session
$event_name = $_POST['event_name'];
$event_start_date = date("y-m-d", strtotime($_POST['event_start_date'])); 
$event_end_date = date("y-m-d", strtotime($_POST['event_end_date'])); 

$insert_query = "INSERT INTO `calendar_event_master`(`event_name`, `event_start_date`, `event_end_date`, `user_id`) 
                 VALUES ('".$event_name."', '".$event_start_date."', '".$event_end_date."', '".$user_id."')";

if(mysqli_query($con, $insert_query)) {
	$data = array(
		'status' => true,
		'msg' => 'Event added successfully!'
	);
} else {
	$data = array(
		'status' => false,
		'msg' => 'Sorry, Event not added.'				
	);
}
echo json_encode($data);

?>
