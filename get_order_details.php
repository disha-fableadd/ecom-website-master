<?php
include 'db.php'; // Ensure the database connection is established
header('Content-Type: application/json');

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    $query = "
        SELECT orders.*, users.*, order_items.product_id,order_items.quantity, products.name, products.price,products.image
        FROM orders
        JOIN users ON orders.user_id = users.id
        JOIN order_items ON orders.order_id = order_items.order_id
        JOIN products ON order_items.product_id = products.id
        WHERE orders.order_id = $orderId
    ";

    $result = mysqli_query($conn, $query);

    // If the query is successful and we get a result
    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);

        // Return the order, user, and product details as a JSON response
        echo json_encode([
            'success' => true,
            'order' => [
                'order_id' => $order['order_id'],
                'quantity' => $order['quantity'],
                'total' => $order['total'],
                'product_id' => $order['product_id'],
                'name' => $order['name'],
                'price' => $order['price'],
                'image' => $order['image']
            ],
            'user' => [
                'email' => $order['email'],
                'mobile' => $order['mobile'],
                'address' => $order['address'],
                'city' => $order['city'],
                'state' => $order['state']
            ]
        ]);
    } else {
        // If the order is not found or there is an error with the query
        echo json_encode([
            'success' => false,
            'message' => 'Order not found or error fetching order details: ' . mysqli_error($conn)
        ]);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If no order_id is provided
    echo json_encode([
        'success' => false,
        'message' => 'Order ID is required'
    ]);
}
?>
