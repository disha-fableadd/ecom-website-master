<?php
include 'header.php';
?>

<div class="container-fluid pt-5">
    <div class="row justify-content-center px-xl-5">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card border-0 shadow-lg p-4">
                <h3 class="text-center mb-4">Register</h3>
                <form id="registerForm" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name"
                                placeholder="Enter your first name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                placeholder="Enter your last name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter a password" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" name="age" id="age" placeholder="Enter your age"
                                required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" id="profile_picture"
                                accept="image/*">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" id="mobile"
                                placeholder="Enter your mobile number" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
            <div id="message" class="text-center mt-3"></div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
<script>
    $(document).ready(function () {
        $('#registerForm').on('submit', function (e) {
            e.preventDefault();

            // Clear any previous messages
            $('#message').html('');

            // Validate fields
            let valid = true;

            // Validate First Name
            if ($('#first_name').val().trim() === '') {
                valid = false;
                $('#message').append('<div class="alert alert-danger">First Name is required.</div>');
            }

            // Validate Last Name
            if ($('#last_name').val().trim() === '') {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Last Name is required.</div>');
            }

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if ($('#email').val().trim() === '' || !emailPattern.test($('#email').val().trim())) {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Enter a valid email address.</div>');
            }

            // Validate Password (min 6 characters)
            if ($('#password').val().trim().length < 6) {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Password must be at least 6 characters long.</div>');
            }

            // Validate Age
            if ($('#age').val().trim() === '' || $('#age').val() < 18) {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Age must be 18 or older.</div>');
            }

            // Validate Gender
            if ($('#gender').val() === '') {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Gender selection is required.</div>');
            }

            // Validate Mobile Number (10 digits)
            const mobilePattern = /^\d{10}$/;
            if ($('#mobile').val().trim() === '' || !mobilePattern.test($('#mobile').val().trim())) {
                valid = false;
                $('#message').append('<div class="alert alert-danger">Enter a valid 10-digit mobile number.</div>');
            }

            // If all validations pass, proceed with AJAX request
            if (valid) {
                const formData = new FormData(this);

                $.ajax({
                    url: 'register_page.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#message').html('<div class="alert alert-success">' + response + '</div>');
                        $('#registerForm')[0].reset();
                        setTimeout(function () {
                            window.location.href = "login.php";
                        }, 3000);
                    },
                    error: function (xhr, status, error) {
                        $('#message').html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');
                    }
                });
            }
        });
    });
</script>
