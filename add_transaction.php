<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'includes/db.php';
$user_id = $_SESSION['user_id'];
$type = $_POST['type']; // 'spend' or 'receive'
$date = $_POST['date'];
$amount = $_POST['amount'];
$description = $_POST['description'];
$tag = $_POST['tag'];

// Insert the transaction into the database
$query = $conn->prepare("INSERT INTO transactions (user_id, type, date, amount, description, tag) VALUES (?, ?, ?, ?, ?, ?)");
$query->bind_param("issdss", $user_id, $type, $date, $amount, $description, $tag);
$query->execute();

header('Location: dashboard.php'); // Redirect back to the dashboard after adding a transaction
exit;
