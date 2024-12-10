<?php

include 'db.php';
session_start();

$total_products = 0;

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(DISTINCT product_id) AS total_products FROM cart WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $total_products = $row['total_products'];
    }
} else {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $total_products = count($_SESSION['cart']);
    }
}

echo json_encode(['total_products' => $total_products]);

mysqli_close($conn);
?>
