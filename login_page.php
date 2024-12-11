<?php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Check if there is cart data in the session
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $user_id = $_SESSION['user_id'];

                // Store cart data in the database for the logged-in user
                foreach ($_SESSION['cart'] as $cart_item) {
                    // Extract product_id and quantity from the cart item
                    $product_id = $cart_item['product_id'];
                    $quantity = $cart_item['quantity'];

                    // Insert the cart data into the database
                    $cart_query = "INSERT INTO cart (user_id, product_id, quantity) 
                                   VALUES ('$user_id', '$product_id', '$quantity')";
                    mysqli_query($conn, $cart_query);
                }

                // Clear session cart after storing it in the database
                unset($_SESSION['cart']);
            }

            echo 'success';  // Indicate successful login
        } else {
            echo "Invalid password. Please try again.";  // Invalid password
        }
    } else {
        echo "No account found with that email. Please register first.";  // Email not found
    }

    mysqli_close($conn);  // Close the database connection
}
?>
