<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $todo_id = intval($_POST['todo_id']);
    $status = isset($_POST['status']) ? 1 : 0;

    $stmt = $con->prepare("UPDATE todos SET completed = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $status, $todo_id, $user_id);

    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
}
