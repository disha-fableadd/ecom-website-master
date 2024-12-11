<?php include 'header.php'; ?>

<!-- Cart Table -->
<div class="container-fluid pt-5" id="cartContainer">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0 ordertable" id="cartTable">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody">
                </tbody>
            </table>

        </div>
        <div class="col-lg-4" id="cartSummary">
            <form class="mb-5" action="">
                <div class="input-group">
                    <input type="text" class="form-control p-4" placeholder="Coupon Code">
                    <div class="input-group-append">
                        <button class="btn btn-primary">Apply Coupon</button>
                    </div>
                </div>
            </form>
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                </div>

                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2" id="grandTotalContainer">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold"><span id="grandTotal">0.00</span></h5>
                    </div>
                    <button class="btn btn-block btn-primary my-3 py-3" id="submitOrder" style="display: none;">
                        <a href="checkout.php" style="color:white; text-decoration: none;"> Proceed To Checkout</a>
                    </button>

                </div>
            </div>
        </div>
        <div id="emptyCartMessage" style="display: none; margin: auto;">
            <div class="text-center ">
                <h4 class="text-muted mb-3">Your cart is empty.</h4>
                <p class="text-muted mb-3">It looks like you haven't added anything to your cart yet.</p>
                <a href="home.php" class="btn btn-primary btn-lg">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
       
            function fetchCart() {
                $.ajax({
                    url: 'fetch_cart.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log("Data fetched: ", data);
                        console.log("Data fetched: ", data);
                        let html = '';
                        let grandTotal = 0;

                        if (data.length === 0) {
                           
                            $('#cartTable').hide();
                            $('#emptyCartMessage').show();
                            $('#cartSummary').hide(); 
                            $('#submitOrder').hide();
                            return;
                        }

                       
                        $('#cartTable').show();
                        $('#emptyCartMessage').hide();
                        $('#cartSummary').show();  
                        $('#submitOrder').show();

                        data.forEach(item => {
                            console.log("Item: ", item);
                            const price = parseFloat(item.price);
                            const totalPrice = price * item.quantity;
                            grandTotal += totalPrice;

                            html += ` 
                    <tr>
                        <td class="align-middle">${item.name}</td>
                        <td class="align-middle"><img src="uploads/${item.image}" alt="${item.name}" width="50"></td>
                        <td class="align-middle">${price.toFixed(2)}</td>
                        <td class="align-middle">
                            <button class="btn btn-sm btn-primary decrease-qty" data-id="${item.id}" data-qty="${item.quantity}">  <i class="fa fa-minus"></i></button>
                            <span class="mx-2">${item.quantity}</span>
                            <button class="btn btn-sm btn-primary increase-qty" data-id="${item.id}" data-qty="${item.quantity}">   <i class="fa fa-plus"></i></button>
                        </td>
                        <td class="align-middle">${totalPrice.toFixed(2)}</td>
                        <td class="align-middle">
                            <button class="btn btn-sm btn-primary remove-item" data-id="${item.id}"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>`;
                        });

                        $('#orderTableBody').html(html);
                        $('#grandTotal').text(grandTotal.toFixed(2));
                    }
                });
            }

    fetchCart();


        // Increase quantity
        $(document).on('click', '.increase-qty', function () {
                    const cartId = $(this).data('id');
        const quantity = parseInt($(this).data('qty')) + 1;

        updateQuantity(cartId, quantity);
                });

        // Decrease quantity
        $(document).on('click', '.decrease-qty', function () {
                    const cartId = $(this).data('id');
        const quantity = parseInt($(this).data('qty')) - 1;

                    if (quantity > 0) {
            updateQuantity(cartId, quantity);
                    }
                });

        // Update quantity function
        function updateQuantity(cartId, quantity) {
            $.ajax({
                url: 'update_cart.php',
                type: 'POST',
                data: { cart_id: cartId, quantity: quantity },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        fetchCart();
                    } else {
                        alert('Failed to update quantity.');
                    }
                }
            });
                }

        // After removing an item from the cart, update the product count
        $(document).on('click', '.remove-item', function () {
                    const cartId = $(this).data('id');

        if (confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                url: 'remove_item.php',
                type: 'POST',
                data: { cart_id: cartId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        fetchCart();
                    } else {
                        alert('Failed to remove item.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error removing item: ", error);
                    alert('An error occurred while removing the item.');
                }
            });
                    }
                });
        
          




</script>

