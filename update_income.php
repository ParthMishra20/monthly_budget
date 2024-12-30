<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'includes/db.php';
$user_id = $_SESSION['user_id'];
$income = $_POST['income']; // The new monthly income from the form

// Get the current month and year in the format 'YYYY-MM'
$month_year = date("Y-m");

// Check if income already exists for the user for the current month
$query = $conn->prepare("SELECT * FROM monthly_income WHERE user_id = ? AND month_year = ?");
$query->bind_param("is", $user_id, $month_year);
$query->execute();
$result = $query->get_result();

// If income exists, update it; otherwise, insert it
if ($result->num_rows > 0) {
    // Update existing income
    $update_query = $conn->prepare("UPDATE monthly_income SET income = ? WHERE user_id = ? AND month_year = ?");
    $update_query->bind_param("dis", $income, $user_id, $month_year);
    $update_query->execute();
    echo "Income updated successfully.";
} else {
    // Insert new income with current month_year
    $insert_query = $conn->prepare("INSERT INTO monthly_income (user_id, income, month_year) VALUES (?, ?, ?)");
    $insert_query->bind_param("ids", $user_id, $income, $month_year);
    $insert_query->execute();
    echo "Income added successfully.";
}

header('Location: dashboard.php'); // Redirect back to the dashboard after updating
exit;
