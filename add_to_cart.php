<?php

include 'db.php';

session_start();

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

$product_id = intval($product_id);
$quantity = intval($quantity);

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $cart = mysqli_fetch_assoc($result);
        $new_quantity = $cart['quantity'] + $quantity;

        $updateQuery = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";

        if (mysqli_query($conn, $updateQuery)) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
        }
    } else {
        $insertQuery = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";

        if (mysqli_query($conn, $insertQuery)) {
            echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product to cart.']);
        }
    }
} else {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        echo json_encode(['status' => 'success', 'message' => 'Product updated in session cart!']);
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
        echo json_encode(['status' => 'success', 'message' => 'Product added to session cart!']);
    }
}

mysqli_close($conn);
?>
