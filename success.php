<?php
session_start();

// Prevent the page from being cached to ensure the user can't navigate back to it after the order
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Check if the order was successful, otherwise redirect to home
if (!isset($_SESSION['order_success']) || $_SESSION['order_success'] !== true) {
    header("Location: home.php"); 
    exit();
}

// Unset the order success session after displaying the page
unset($_SESSION['order_success']);

include 'header.php';
?>

<div class="container text-center py-5">
    <div class="d-flex justify-content-center align-items-center flex-column">
        <i class="fa fa-check-circle text-success" style="font-size: 100px;"></i>
        <h1 class="mt-4">Order Placed Successfully!</h1>
        <p class="mt-3">Thank you for your order.</p>
        <a href="home.php" class="btn btn-primary mt-3">Continue Shopping</a>
    </div>
</div>

<?php
include 'footer.php';
?>
