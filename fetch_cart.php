<?php
include 'db.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$cart_items = [];

if ($user_id) {
    // Logged-in user cart fetch
    $sql = "SELECT c.id, p.name, p.image, p.price, c.quantity FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = '$user_id'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cart_items[] = $row;
        }
    }
} else{
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        foreach ($cart as $product_id => $items) {
            $sql = "SELECT * FROM products WHERE id = $product_id";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);

                $cart_items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $items['quantity']
                ];
            } else {
                $cart_items[] = [
                    'id' => $product_id,
                    'name' => isset($items['name']) ? $items['name'] : 'Unknown Product',
                    'price' => isset($items['price']) ? $items['price'] : 0.00,
                    'image' => isset($items['image']) ? $items['image'] : 'default.jpg',
                    'quantity' => isset($items['quantity']) ? $items['quantity'] : 1
                ];
            }
        }
    }
}

// Return the data as JSON
echo json_encode($cart_items);

mysqli_close($conn);
?>
