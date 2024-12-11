<?php
include 'db.php';
session_start();



if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Input Validation
    $required_fields = ['first_name', 'last_name', 'email', 'mobile', 'address', 'zip_code', 'city', 'state', 'total'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.']);
            exit;
        }
    }

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
        
        $order_id = mysqli_insert_id($conn);

       
        $cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'";
        $cart_result = mysqli_query($conn, $cart_sql);

        if (mysqli_num_rows($cart_result) > 0) {
            $order_items_success = true; 

            while ($cart_item = mysqli_fetch_assoc($cart_result)) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];

                $order_items_sql = "INSERT INTO order_items (order_id, product_id, quantity) 
                                    VALUES ('$order_id', '$product_id', '$quantity')";

                if (!mysqli_query($conn, $order_items_sql)) {
                    $order_items_success = false;
                    break;
                }
            }

            if ($order_items_success) {
                // Clear the cart after successful order placement
                $delete_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
                if (mysqli_query($conn, $delete_cart_sql)) {
                    $_SESSION['order_success'] = true; // Set success flag
                    echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Order placed, but cart clearing failed.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to insert order items.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No items in cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error placing order: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit;
}
?>
