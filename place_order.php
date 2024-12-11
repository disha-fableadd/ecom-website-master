<?php
include 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);

    // Insert into orders table
    $sql = "INSERT INTO orders (user_id, first_name, last_name, email, mobile, address, zip_code, city, state, total) 
            VALUES ('$user_id','$first_name', '$last_name', '$email', '$mobile', '$address', '$zip_code', '$city', '$state', '$total')";

    if (mysqli_query($conn, $sql)) {
        // Get the order_id of the newly inserted order
        $order_id = mysqli_insert_id($conn);

            $cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
        $cart_result = mysqli_query($conn, $cart_sql);
        
        if (mysqli_num_rows($cart_result) > 0) {
            // Proceed with inserting order items
            while ($cart_item = mysqli_fetch_assoc($cart_result)) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
        
                $order_items_sql = "INSERT INTO order_items (order_id, product_id, quantity) 
                                    VALUES ('$order_id', '$product_id', '$quantity')";
                if (!mysqli_query($conn, $order_items_sql)) {
                    echo json_encode(['success' => false, 'message' => 'Error inserting order item: ' . mysqli_error($conn)]);
                    exit;
                }
            }
        
            // Clear the cart after successful order placement
            $delete_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
            if (mysqli_query($conn, $delete_cart_sql)) {
                echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order placed, but cart clearing failed: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No items in cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit;
}
?>
