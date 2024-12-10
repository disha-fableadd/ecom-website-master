<?php
include 'db.php';
session_start();

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    // If user is not logged in, redirect to login page
    $_SESSION['redirect_after_login'] = 'checkout.php'; // Save the redirect URL
    header("Location: login.php");
    exit();
} else {
    // User is logged in
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Transfer session cart data to the database
        $session_cart = $_SESSION['cart'];

        foreach ($session_cart as $product_id => $item) {
            $quantity = $item['quantity'];
            
            // Check if the product already exists in the user's cart
            $sql = "SELECT id FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Update the quantity if the product already exists
                $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
            } else {
                // Insert new product into the cart
                $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
            }

            mysqli_query($conn, $sql);
        }

        // Clear the session cart
        unset($_SESSION['cart']);
    }

    // Redirect to the checkout page
    header("Location: checkout.php");
    exit();
}

mysqli_close($conn);
?>
