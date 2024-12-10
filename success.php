<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
