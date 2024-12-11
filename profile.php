<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'header.php';

?>
<link rel="stylesheet" href="styles.css">



<section class="my-5">
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="" alt="Admin" class="rounded-circle p-1 bg-warning" width="110">
                                <div class="mt-3">
                                    <h4></h4>
                                    <p class="text-dark mb-1">+91 </p>
                                    <p class="text-muted font-size-sm"></p>
                                </div>
                            </div>
                            <div class="list-group list-group-flush text-center mt-4">
                                <a href="#" class="list-group-item list-group-item-action border-0 "
                                    onclick="showProfileDetails()">
                                    Profile Informaton
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0"
                                    onclick="showOrderDetails()">Orders</a>

                                <a href="#" class="list-group-item list-group-item-action border-0 active"
                                    onclick="showAddressBook()">
                                    Change Password
                                </a>
                                <a href="logout.php" class="list-group-item list-group-item-action border-0">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="profileDetails" class="card" style="display: none;">
                        <div class="card-body">
                            <div class="profile-info">

                            </div>

                        </div>
                        <!-- Modal for editing profile -->
                        <div id="editProfileModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span class="close" onclick="closeEditProfileModal()">&times;</span>
                                <h2>Edit Profile</h2>
                                <form id="editProfileForm" onsubmit="saveProfile(event)">
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="first_name">First Name:</label>
                                        <input class="form-control" type="text" id="first_name"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="last_name">Last Name:</label>
                                        <input class="form-control" type="text" id="last_name"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="email">Email:</label>
                                        <input class="form-control" type="email" id="email"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="mobile">Mobile Number:</label>
                                        <input class="form-control" type="tel" id="mobile" pattern="[0-9]{10}"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="age">Age:</label>
                                        <input class="form-control" type="number" id="age"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="gender">Gender:</label>
                                        <select class="form-control" id="gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select><br>
                                    </div>
                                    <div class="col-12 d-flex button_div">
                                        <button type="submit" class="btn btn-outline-primary">Save</button>
                                        <button type="button" onclick="closeEditProfileModal()"
                                            class="btn btn-outline-primary">Cancel</button>
                                    </div>
                                    <div id="responseMessage"
                                        style="display:none; padding: 10px; background-color: #f1f1f1; border: 1px solid #ccc; margin-top: 10px;">
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                    <!-- Order Details View Section -->
                    <div id="orderDetails" class="" style="display: none;">
                        <div class="card mt-4 p-5">
                            <div class="card-body p-0">
                                <h4 class="text-center mb-0">Order Details</h4>
                                <div id="order-details-container">
                                    <!-- Order details will be injected here -->
                                </div>
                                <button id="back-to-orders-btn" class="btn btn-primary mt-3" style="float:right;">Back
                                    to Orders</button>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table Section -->
                    <div id="ordersTable" class="" style="display: none;">
                        <div class="card mt-4">
                            <div class="card-body p-0 table-responsive">
                                <h4 class="p-3 mb-0">Orders</h4>
                                <table id="orders-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>PName</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Orders will be populated here by AJAX -->
                                    </tbody>
                                </table>
                                <div id="message-container"></div>
                            </div>
                        </div>
                    </div>




                    <div id="addressBook" class="card" style="display: none;">
                        <div class="card-body">
                            <h5>Change Password</h5>
                            <button class="btn btn-primary mt-3 change" onclick="showAddAddressModal()">Change
                                Password</button>

                            <div id="addressList">

                            </div>
                        </div>
                    </div>

                    <div id="addAddressModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeAddAddressModal()">&times;</span>
                            <h2 class="mb-5">Change Password</h2>
                            <form id="addAddressForm" onsubmit="saveAddress(event)">
                                <div class="row">
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="current_password">Current Password:</label>
                                        <input class="form-control" type="password" id="current_password"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div">
                                        <label for="new_password">New Password:</label>
                                        <input class="form-control" type="password" id="new_password"><br>
                                    </div>
                                    <div class="col-12 d-flex flex-column inner_flex_div mt-3">
                                        <label for="confirm_password">Confirm Password:</label>
                                        <input class="form-control" type="password" id="confirm_password"><br>
                                    </div>
                                </div>

                                <div class="col-12 d-flex button_div">
                                    <button type="submit" class="btn btn-outline-primary">Save</button>
                                    <button type="button" onclick="closeAddAddressModal()"
                                        class="btn btn-outline-primary">Cancel</button>
                                </div>
                                <br>
                                <div id="responseMessage1"
                                    style="display:none; padding: 10px; background-color: #f1f1f1; border: 1px solid #ccc; margin-top: 10px;">
                                </div>
                            </form>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

<script>

    function showAddAddressModal() {
        const modal = document.getElementById('addAddressModal');
        modal.style.display = 'block';
        isFormVisible = true;
    }

    function closeAddAddressModal() {
        const modal = document.getElementById('addAddressModal');
        modal.style.display = 'none';
        isFormVisible = false;
    }

    function showProfileDetails() {
        hideAllSections();
        document.getElementById('profileDetails').style.display = 'block';
        setActiveLink(0);
    }

    function showOrderDetails() {
        hideAllSections();
        document.getElementById('ordersTable').style.display = 'block';
        setActiveLink(1);
    }

    function showAddressBook() {
        hideAllSections();
        document.getElementById('addressBook').style.display = 'block';
        setActiveLink(2);
    }

    function hideAllSections() {
        document.getElementById('ordersTable').style.display = 'none';
        document.getElementById('profileDetails').style.display = 'none';
        document.getElementById('addressBook').style.display = 'none';
    }

    function setActiveLink(index) {
        document.querySelector('.list-group-item.active').classList.remove('active');
        document.querySelectorAll('.list-group-item')[index].classList.add('active');
    }
    function closeEditProfileModal() {
        document.getElementById('editProfileModal').style.display = 'none';
    }

    function openEditProfileModal() {
        document.getElementById('editProfileModal').style.display = 'block';
    }

    window.onclick = function (event) {
        var modal = document.getElementById('editProfileModal');
        if (event.target === modal) {
            closeEditProfileModal();
        }
    }

    showProfileDetails();
</script>

<?php

include 'footer.php';
?>
<script>
    function editProfile() {
        document.getElementById('editProfileModal').style.display = 'block';

        $.ajax({
            url: "fetch_user.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $("#first_name").val(response.first_name);
                    $("#last_name").val(response.last_name);
                    $("#email").val(response.email);
                    $("#mobile").val(response.mobile);
                    $("#age").val(response.age);
                    $("#gender").val(response.gender);
                }
            }

        });
    }



    function saveProfile(event) {
        event.preventDefault(); // Prevent form from submitting the default way

        const data = {
            first_name: $("#first_name").val(),
            last_name: $("#last_name").val(),
            email: $("#email").val(),
            mobile: $("#mobile").val(),
            age: $("#age").val(),
            gender: $("#gender").val(),
        };

        $.ajax({
            url: "update_profile.php",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {

                $('#responseMessage').text(response.success).show().css('background-color', 'green').css('color', 'white');
                setTimeout(function () {
                    window.location.href = 'profile.php';
                }, 2000);

            },
            error: function (xhr, status, error) {

                $('#responseMessage').text("An error occurred while Updating Your Profile. Please try again.").show().css('background-color', 'red').css('color', 'white');
            }

        });
    }

    $(document).ready(function () {
        $.ajax({
            url: "fetch_user.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    // Update profile card details
                    $(".d-flex.align-items-center.text-center img").attr("src", response.profile_picture ? `uploads/${response.profile_picture}` : "uploads/default-profile.png");
                    $(".d-flex.align-items-center.text-center h4").text(`${response.first_name} ${response.last_name}`);
                    $(".d-flex.align-items-center.text-center .text-dark").text(`+91 ${response.mobile}`);
                    $(".d-flex.align-items-center.text-center .text-muted").text(response.email);

                    // Update detailed profile information
                    $("#profileDetails .profile-info").html(`
                    <h5>Profile Information</h5>
                    <p><strong>Name:</strong> ${response.first_name} ${response.last_name}</p>
                    <p><strong>Email Address:</strong> ${response.email}</p>
                    <p><strong>Contact:</strong> ${response.mobile}</p>
                    <p><strong>Age:</strong> ${response.age}</p>
                    <p><strong>Gender:</strong> ${response.gender}</p>
                     <button class="btn btn-primary mt-3" onclick="editProfile()">Edit Profile</button>
                    
                `);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });


    function saveAddress(event) {
        event.preventDefault();

        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();

        if (newPassword !== confirmPassword) {
            alert("New password and confirm password do not match!");
            return;
        }

        $.ajax({
            url: 'change_password.php',
            method: 'POST',
            data: {
                current_password: currentPassword,
                new_password: newPassword
            },
            success: function (response) {
                var data = JSON.parse(response);

                if (data.status === 'success') {
                    $('#responseMessage1').text(data.message).show().css('background-color', 'green').css('color', 'white');
                    setTimeout(function () {
                        window.location.href = 'profile.php';
                    }, 3000);
                } else {
                    $('#responseMessage1').text(data.message).show().css('background-color', 'red').css('color', 'white');
                }
            },
            error: function (xhr, status, error) {
                $('#responseMessage1').text("An error occurred while changing the password. Please try again.").show().css('background-color', 'red').css('color', 'white');
            }
        });


    }

    function closeAddAddressModal() {
        $('#addAddressModal').hide();
    }

    $(document).ready(function () {
        $.ajax({
            url: 'fetch_orders.php', // PHP file to fetch the orders
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    var orders = response.orders;
                    var tableBody = $('#orders-table tbody');
                    tableBody.empty(); // Clear the table before adding new rows

                    if (orders.length > 0) {
                        orders.forEach(function (order) {
                            tableBody.append(
                                `<tr>
                <td>${order.product_name}</td>
                <td>${order.product_price}</td>
                <td>${order.quantity}</td>
                <td>${order.total}</td>
                <td>
                    <button class="view-order-btn text-info" data-id="${order.order_id}" style="color:blue;">
                        👁️
                    </button>
                    <button class="delete-order-btn text-danger" data-id="${order.order_id}" style="color:red;">
                        🗑️
                    </button>
                </td>
            </tr>`
                            );
                        });
                    } else {
                        tableBody.append('<tr><td colspan="13">No orders found.</td></tr>');
                    }

                } else {
                    // Handle the error (e.g., user not logged in)
                    alert(response.message);
                }
            },
            error: function () {
                alert('Error fetching orders.');
            }
        });
    });
    $(document).on('click', '.delete-order-btn', function () {
        const orderId = $(this).data('id');
        const row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this order?')) {
            $.ajax({
                url: 'delete_order.php', // Backend script
                type: 'POST',
                data: { order_id: orderId },
                success: function (response) {
                    const res = JSON.parse(response);
                    const messageContainer = $('#message-container');

                    if (res.success) {
                        row.remove(); // Remove the row from the table
                        messageContainer.html(`<div style="color: green;">${res.message}</div>`);
                    } else {
                        messageContainer.html(`<div style="color: red;">Failed to delete order: ${res.message}</div>`);
                    }
                },
                error: function () {
                    $('#message-container').html('<div style="color: red;">An error occurred while deleting the order.</div>');
                }

            });
        }
    });

    $(document).on('click', '.view-order-btn', function () {
        var orderId = $(this).data('id');
        console.log(orderId);


        $('#ordersTable').css('display', 'none');
        $('#orderDetails').css('display', 'block');

        $.ajax({
            url: 'get_order_details.php',
            type: 'GET',
            data: { order_id: orderId },
            success: function (response) {
                console.log(response);

                var data = response;

                if (data.success) {
                    var order = data.order;
                    var user = data.user;

                    var detailsHtml = `
                        <h3>Order Details</h3>
                        <div style="display: flex; align-items: center;">
                            <div style="flex: 1;">
                                <p><strong>Product Name:</strong> ${order.name}</p>
                                <p><strong>Price:</strong> ${order.price}</p>
                                <p><strong>Quantity:</strong> ${order.quantity}</p>
                                <p><strong>Total:</strong> ${order.total}</p>
                            </div>
                            <div style="flex: 0 0 150px; padding-left: 20px;">
                                <img src="uploads/${order.image}" alt="${order.name}" style="width: 100%; max-width: 250px; height: auto;">
                            </div>
                        </div>

                        <h4>User Information</h4>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Mobile:</strong> ${user.mobile}</p>
                        <p><strong>Address:</strong> ${user.address}</p>
                        <p><strong>City:</strong> ${user.city}</p>
                        <p><strong>State:</strong> ${user.state}</p>
                    `;

                    $('#order-details-container').html(detailsHtml);

                } else {
                    alert('Failed to fetch order details');
                }
            }
        });
    });

    $('#back-to-orders-btn').on('click', function () {
        $('#orderDetails').css('display', 'none');
        $('#ordersTable').css('display', 'block');
    });



</script>