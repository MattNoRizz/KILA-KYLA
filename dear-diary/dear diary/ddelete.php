<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$noteId = $_POST['id'];

/** @var Connection $connection */
$connection = require_once 'pdo.php';

// Get the actual PDO instance
$pdo = $connection->getPdo();


// Delete the note only if it belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'id' => $noteId,
    'user_id' => $userId
]);

header("Location: diary.php");
exit();
