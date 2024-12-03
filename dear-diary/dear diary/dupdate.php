<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

$userId = $_SESSION['user_id'];
$noteId = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];

/** @var Connection $connection */
$connection = require_once 'pdo.php';

// Get the actual PDO instance
$pdo = $connection->getPdo();


// Update the note only if it belongs to the logged-in user
$stmt = $pdo->prepare("UPDATE notes SET title = :title, description = :description WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'title' => $title,
    'description' => $description,
    'id' => $noteId,
    'user_id' => $userId
]);

header("Location: view_note.php");
exit();
