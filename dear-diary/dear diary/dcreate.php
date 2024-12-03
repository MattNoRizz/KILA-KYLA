<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

// Retrieve the title, description, and user ID from the form
$title = $_POST['title'];
$userId = $_SESSION['user_id']; // Logged-in user's ID
$description = $_POST['description'];

// Check if the request is for updating an existing note
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    // The form has an ID, meaning we are updating an existing note
    $noteId = (int)$_POST['id'];

    /** @var Connection $connection */
    $connection = require_once 'pdo.php';

    // Get the actual PDO instance
    $pdo = $connection->getPdo();

    // Prepare and execute the update query
    $stmt = $pdo->prepare("UPDATE notes SET title = :title, description = :description WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'id' => $noteId,
        'user_id' => $userId
    ]);
} else {
    // The form does not have an ID, meaning we are creating a new note
    /** @var Connection $connection */
    $connection = require_once 'pdo.php';

    // Get the actual PDO instance
    $pdo = $connection->getPdo();

    // Prepare and execute the insert query
    $stmt = $pdo->prepare("INSERT INTO notes (title, user_id, description, create_date) 
                           VALUES (:title, :user_id, :description, NOW())");

    $stmt->execute([
        'title' => $title,
        'user_id' => $userId,
        'description' => $description
    ]);
}

// Redirect to the diary page after success
header("Location: diary.php");
exit();
