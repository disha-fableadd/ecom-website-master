<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get orders for the specific user with product details
$sql = "
    SELECT 
    o.order_id,
        o.first_name, 
        o.mobile, 
        o.address, 
        o.city, 
        o.total, 
        p.name AS product_name, 
        p.price AS product_price,
        oi.quantity
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = '$user_id'
";

// Execute the query
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Error fetching orders: ' . mysqli_error($conn)]);
    exit();
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

mysqli_close($conn);

echo json_encode(['success' => true, 'orders' => $orders]);
?>
