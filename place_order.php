<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Order details
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert into orders table
        $sql = "INSERT INTO orders (user_id, first_name, last_name, email, mobile, address, zip_code, city, state, total) 
                VALUES ('$user_id', '$first_name', '$last_name', '$email', '$mobile', '$address', '$zip_code', '$city', '$state', '$total')";

        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn);

            // Fetch cart items from cart table
            $cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
            $cart_result = mysqli_query($conn, $cart_sql);

            if ($cart_result && mysqli_num_rows($cart_result) > 0) {
                // Insert each cart item into the order_items table
                while ($cart_row = mysqli_fetch_assoc($cart_result)) {
                    $product_id = $cart_row['product_id'];
                    $quantity = $cart_row['quantity'];

                    // Insert into order_items table
                    $order_items_sql = "INSERT INTO order_items (order_id, product_id, quantity) 
                                        VALUES ('$order_id', '$product_id', '$quantity')";
                    if (!mysqli_query($conn, $order_items_sql)) {
                        throw new Exception('Failed to add product to order items.');
                    }
                }

                // Now clear the cart after order placement
                $delete_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
                if (!mysqli_query($conn, $delete_cart_sql)) {
                    throw new Exception('Failed to clear cart.');
                }

                // Commit transaction
                mysqli_commit($conn);
                echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
            } else {
                throw new Exception('Cart is empty.');
            }
        } else {
            throw new Exception('Error placing order.');
        }
    } catch (Exception $e) {
        // Rollback on error
        mysqli_roll_back($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    mysqli_close($conn);
}
?>
