<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $todo_name = htmlspecialchars(trim($_POST['todo_name']));

    $stmt = $con->prepare("INSERT INTO todos (user_id, todo_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $todo_name);

    if ($stmt->execute()) {
        header("Location: todo.php");
    } else {
        echo "Error adding to-do.";
    }

    $stmt->close();
}
