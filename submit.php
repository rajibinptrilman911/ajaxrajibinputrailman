<?php
session_start();
require 'Connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$name = $_POST['name'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO entries (name, message) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $message);
$stmt->execute();

header("Location: index.php");
exit;
?>


