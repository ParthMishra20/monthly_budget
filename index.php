<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/db.php';
    $username = $_POST['username'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username) VALUES (?)");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $_SESSION['user_id'] = $conn->insert_id;
    }

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Username Input</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <form method="post">
        <label for="username">Enter Username:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
