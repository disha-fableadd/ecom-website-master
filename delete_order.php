<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);

    // Validate the order ID
    if ($order_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID.']);
        exit;
    }

    // Delete the order
    $sql = "DELETE FROM orders WHERE order_id = '$order_id'";
    if (mysqli_query($conn, $sql)) {
        // Delete associated items in the order_items table
        $delete_items_sql = "DELETE FROM order_items WHERE order_id = '$order_id'";
        mysqli_query($conn, $delete_items_sql);

        echo json_encode(['success' => true, 'message' => 'Order deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete order: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit;
}
?>
