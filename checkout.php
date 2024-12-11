<?php
include 'db.php';
session_start();


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    header("Location: login.php"); 
    exit();
}


$user_details = [];
if ($user_id) {
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user_details = mysqli_fetch_assoc($result);
    } else {
        echo "No user found with this ID.";
    }
}

$cart_sql = "
    SELECT p.name, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_sql);

if (!$cart_result) {
    die("Cart query failed: " . mysqli_error($conn));
}

$total = 0;
$cart_items = [];

if (mysqli_num_rows($cart_result) > 0) {
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}
else{
    echo "no product in cart..";
}

mysqli_close($conn);
?>


<?php include 'header.php'; ?>

<form id="checkout-form">
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input class="form-control" type="text" name="first_name"
                                value="<?php echo $user_details['first_name'] ?? ''; ?>" placeholder="Firstname">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- Last Name -->
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" type="text" name="last_name"
                                value="<?php echo $user_details['last_name'] ?? ''; ?>" placeholder="Lastname">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" type="email" name="email"
                                value="<?php echo $user_details['email'] ?? ''; ?>" placeholder="example@email.com">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- Mobile No -->
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" type="text" name="mobile"
                                value="<?php echo $user_details['mobile'] ?? ''; ?>" placeholder="+123 456 789">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- Address -->
                        <div class="col-md-6 form-group">
                            <label>Address Line 1</label>
                            <input class="form-control" type="text" name="address" placeholder="123 Street">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- ZIP Code -->
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" type="text" name="zip_code" placeholder="123">
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- City -->
                        <div class="col-md-6 form-group">
                            <label>City</label>
                            <select class="custom-select form-control" name="city">
                                <option selected disabled>Select City</option>
                                <option value="Surat">Surat</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Rajkot">Rajkot</option>
                            </select>
                            <div class="error-message" style="color: red;"></div>
                        </div>
                        <!-- State -->
                        <div class="col-md-6 form-group">
                            <label>State</label>
                            <select class="custom-select form-control" name="state">
                                <option selected disabled>Select State</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Maharashtra">Maharashtra</option>
                            </select>
                            <div class="error-message" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Products</h5>
                        <?php if (count($cart_items) > 0): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="d-flex justify-content-between">
                                    <p><?php echo $item['name']; ?>
                                        (<?php echo $item['quantity']; ?>)(<?php echo $item['price']; ?>)</p>
                                    <p>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No products in the cart.</p>
                        <?php endif; ?>
                        <hr>
                        <div class="card-footer border-secondary bg-transparent">
                            <div class="d-flex justify-content-between">
                                <h5>Total</h5>
                                <h5>$<?php echo number_format($total, 2); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <button type="button" id="place-order"
                            class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalMessage"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $("#place-order").click(function () {
            $(".error-message").text("");

            let isValid = true;

            const formData = {
                first_name: $("input[name='first_name']").val(),
                last_name: $("input[name='last_name']").val(),
                email: $("input[name='email']").val(),
                mobile: $("input[name='mobile']").val(),
                address: $("input[name='address']").val(),
                zip_code: $("input[name='zip_code']").val(),
                city: $("select[name='city']").val(),
                state: $("select[name='state']").val(),
                total: <?php echo $total; ?>, // Pass PHP variable to JS
                user_id: <?php echo $user_id ? $user_id : 'null'; ?> // Pass user_id
            };
            if (formData.first_name === "") {
                $("input[name='first_name']").next(".error-message").text("First name is required.");
                isValid = false;
            }
            if (formData.last_name === "") {
                $("input[name='last_name']").next(".error-message").text("Last name is required.");
                isValid = false;
            }
            if (formData.email === "" || !/\S+@\S+\.\S+/.test(formData.email)) {
                $("input[name='email']").next(".error-message").text("Valid email is required.");
                isValid = false;
            }
            if (formData.mobile === "" || !/^\d{10}$/.test(formData.mobile)) {
                $("input[name='mobile']").next(".error-message").text("Valid mobile number is required.");
                isValid = false;
            }
            if (formData.address === "") {
                $("input[name='address']").next(".error-message").text("Address is required.");
                isValid = false;
            }
            if (formData.zip_code === "") {
                $("input[name='zip_code']").next(".error-message").text("ZIP code is required.");
                isValid = false;
            }
            if (formData.city === null) {
                $("select[name='city']").next(".error-message").text("City is required.");
                isValid = false;
            }
            if (formData.state === null) {
                $("select[name='state']").next(".error-message").text("State is required.");
                isValid = false;
            }

            if (!isValid) {
                return;
            }
            

            $.ajax({
                url: "place_order.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    $("#modalMessage").text(response.message);
                    $("#orderModal").modal('show');

                    if (response.success) {
                        setTimeout(function () {
                            window.location.href = "success.php";
                        }, 2000);
                    }
                },
                error: function () {
                    $("#modalMessage").text("An error occurred while placing the order.");
                    $("#orderModal").modal('show');
                }
            });
        });
        
    });
</script>