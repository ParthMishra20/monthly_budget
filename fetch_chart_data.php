<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'includes/db.php';
$user_id = $_SESSION['user_id'];

// Get yearly spending data (e.g., by month)
$yearly_query = $conn->prepare("SELECT MONTH(date) AS month, SUM(amount) AS total_spent FROM transactions WHERE user_id = ? GROUP BY MONTH(date) ORDER BY MONTH(date)");
$yearly_query->bind_param("i", $user_id);
$yearly_query->execute();
$yearly_result = $yearly_query->get_result();

$yearly_labels = [];
$yearly_spending = [];

while ($row = $yearly_result->fetch_assoc()) {
    $yearly_labels[] = date("F", mktime(0, 0, 0, $row['month'], 1)); // Get full month name
    $yearly_spending[] = $row['total_spent'];
}

// Get monthly spending data (e.g., by tag)
$monthly_query = $conn->prepare("SELECT tag, SUM(amount) AS total_spent FROM transactions WHERE user_id = ? GROUP BY tag");
$monthly_query->bind_param("i", $user_id);
$monthly_query->execute();
$monthly_result = $monthly_query->get_result();

$monthly_labels = [];
$monthly_spending = [];

while ($row = $monthly_result->fetch_assoc()) {
    $monthly_labels[] = ucfirst($row['tag']); // Capitalize tag name
    $monthly_spending[] = $row['total_spent'];
}

// Return data in JSON format
echo json_encode([
    'yearly' => [
        'labels' => $yearly_labels,
        'spending' => $yearly_spending,
    ],
    'monthly' => [
        'labels' => $monthly_labels,
        'spending' => $monthly_spending,
    ]
]);
?>
