<?php
include 'db.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];

    // Move cart items from session to database
    foreach ($cart as $product_id => $item) {
        $quantity = $item['quantity'];

        // Check if product already exists in the cart table for this user
        $check_cart_sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $check_cart_result = mysqli_query($conn, $check_cart_sql);

        if (mysqli_num_rows($check_cart_result) > 0) {
            // If product exists, update the quantity
            $update_cart_sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
            mysqli_query($conn, $update_cart_sql);
        } else {
            // If product does not exist, insert a new record
            $insert_cart_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
            mysqli_query($conn, $insert_cart_sql);
        }
    }

    // Clear the session cart after transferring to the database
    unset($_SESSION['cart']);
}
?>
