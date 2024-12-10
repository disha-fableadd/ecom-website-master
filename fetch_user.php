<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}



$user_id = intval($_SESSION['user_id']);

// Query to fetch user data
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode($user); // Return user data as JSON
} else {
    echo json_encode(['error' => 'User not found']);
}

mysqli_free_result($result); // Free result set
mysqli_close($conn);
?>
