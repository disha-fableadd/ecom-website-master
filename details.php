<?php
include 'db.php';
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        header("Location: 404.php");
        exit();
    }
} else {
    header("Location: 404.php");
    exit();
}
?>

<?php include 'header.php'; ?>

<!-- Product Details Start -->
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img class="img-fluid" src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 500px;height:500px;">
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <h2><?php echo $product['name']; ?></h2>
            <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
            <div class="d-flex justify-content-start">
                <h3>$<?php echo $product['price']; ?></h3>
            </div>
            
            <!-- Rating Section -->
            <div class="product-rating mt-3">
                <h5>Customer Ratings:</h5>
                <div class="stars">
                    <?php
                    $rating = 4; // For example, the product has 4 out of 5 stars
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<i class="fas fa-star"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                </div>
                <p>Based on 120 reviews</p>
            </div>
            
            <!-- Add to Cart Button -->
            <button class="btn btn-primary mt-3 add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
           
            <h4 class="mt-3" style="font-size: 12px;">Share this product:</h4>
            <div class="social-icons">
                <a href="https://facebook.com" target="_blank" class="btn btn-facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com" target="_blank" class="btn btn-twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank" class="btn btn-instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://pinterest.com" target="_blank" class="btn btn-pinterest"><i class="fab fa-pinterest"></i></a>
            </div>
            <br><br><br>
            <div id="message"></div>

        </div>
       
    </div>

    <!-- Extra Description & Reviews -->
    <div class="row mt-5">
        <div class="col-lg-12">
            <h4>Extra Information</h4>
            <p>Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.</p>
        </div>
    </div>
    
    <!-- Customer Reviews -->
    <div class="row mt-5">
        <div class="col-lg-12">
            <h4>Customer Reviews</h4>
            <div class="review">
                <h5>John Doe</h5>
                <p style="color:#f39c12; font-size:18px"><strong style="color:black;font-size:14px">Rating:</strong> ★★★★☆</p>
                <p>This product is amazing! The quality is top-notch and it fits perfectly.</p>
            </div>
            <div class="review">
                <h5>Jane Smith</h5>
                <p style="color:#f39c12; font-size:18px"><strong style="color:black;font-size:14px">Rating:</strong> ★★★☆☆</p>
                <p>Good product, but the size was a bit smaller than expected. Still, a great buy!</p>
            </div>
        </div>
    </div>
    
</div>

<?php include 'footer.php'; ?>

<!-- Modal for Cart Message -->
<div class="modal fade" id="cartMessageModal" tabindex="-1" role="dialog" aria-labelledby="cartMessageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartMessageModalLabel">Cart Update</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="cartMessageBody">
        <!-- Success or Failure message will be inserted here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="cart.php" class="btn btn-primary">Go to Cart</a>
      </div>
    </div>
  </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id'); 
        var quantity = 1; 
        
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType:"json",
            success: function(response) {
                // Open the modal
                $('#cartMessageModal').modal('show');
                
                if (response.status == 'success') {
                    // Success message
                    $('#cartMessageBody').html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    // Failure message
                    $('#cartMessageBody').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            }
        });
    });
});
$(document).ready(function () {
    $('#cartMessageModal .btn-secondary').click(function () {
        $.ajax({
            url: 'get_cart_count.php', 
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#cart-count').text(response.count);
                } else {
                    console.error('Failed to update cart count:', response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });

        location.reload();
    });
});

</script>
