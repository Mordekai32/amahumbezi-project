<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if(!isset($_GET['id'])){
    header("Location: manage_drinks.php");
    exit();
}

$drink_id = intval($_GET['id']);

// Delete the drink
$stmt = $conn->prepare("DELETE FROM drinks WHERE id=?");
$stmt->bind_param("i", $drink_id);
$stmt->execute();

// Redirect back to manage drinks page
header("Location: manage_drinks.php");
exit();
?>
