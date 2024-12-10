<?php
session_start();
include 'db.php';

// Assuming the user is logged in and their user_id is stored in session
$user_id = $_SESSION['user_id'];  // Replace with how you store/get the user_id

// Check if the cart exists in the session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];

    // Insert each cart item into the database
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        // Insert each cart item into the cart table
        $query = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES ('$user_id', '$product_id', '$quantity', '$price')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
    }

    // Clear the session cart after the data has been moved
    unset($_SESSION['cart']);

    // Redirect the user to the checkout page
    header("Location: checkout.php");
    exit();
} else {
    echo "Cart is empty.";
}
?>
