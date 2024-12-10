<?php
include 'db.php';
session_start();

if (isset($_POST['productId'])) {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $product_id = $_POST['productId'];

    // Delete item from cart
    $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if (mysqli_query($conn, $sql)) {
        echo 'Item deleted successfully';
    } else {
        echo 'Error deleting item';
    }
}
?>
