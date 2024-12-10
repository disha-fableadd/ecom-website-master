// update_user_cart.php

<?php
session_start();
include 'db.php';

// Ensure the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id(); // Session ID of the guest user

if ($user_id) {
    // Update cart items where user_id is NULL (for guest users)
    $update_cart_query = "UPDATE cart SET user_id = '$user_id' WHERE user_id IS NULL AND session_id = '$session_id'";
    $result = mysqli_query($conn, $update_cart_query);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Cart updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update cart."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
}

mysqli_close($conn);
?>
