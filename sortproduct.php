<?php
include 'db.php';


$categoryFilter = isset($_GET['category']) ? $_GET['category'] : null;


if ($categoryFilter) {
    $sql = "SELECT * FROM products WHERE category = '$categoryFilter'";  
} else {
    $sql = "SELECT * FROM products";  
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
            <div class="card product-item border-0 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="uploads/<?php echo $row['image']; ?>" alt="Product">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3"><?php echo $row['name']; ?></h6>
                    <div class="d-flex justify-content-center">
                        <h6>$<?php echo $row['price']; ?></h6>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="view_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                    <a href="cart.php?add=<?php echo $row['id']; ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p class='text-center'>No products found.</p>";
}
?>
