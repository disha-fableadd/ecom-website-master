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
                                placeholder="Enter your first name" >
                                <div id="message1" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                placeholder="Enter your last name" >
                                <div id="message2" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Enter your email" >
                                <div id="message3" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter a password" >
                                <div id="message4" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" name="age" id="age" placeholder="Enter your age"
                                >
                                <div id="message5" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" name="gender" id="gender" >
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="message6" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" id="profile_picture"
                                accept="image/*">
                                <div id="message7" class="text-center text-danger"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" id="mobile"
                                placeholder="Enter your mobile number" >
                                <div id="message8" class="text-center text-danger"></div>
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
                $('#message1').append('<div class="">First Name is require .</div>');
            }

            // Validate Last Name
            if ($('#last_name').val().trim() === '') {
                valid = false;
                $('#message2').append('<div class="">Last Name is require .</div>');
            }

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if ($('#email').val().trim() === '' || !emailPattern.test($('#email').val().trim())) {
                valid = false;
                $('#message3').append('<div class="">Enter a valid email address.</div>');
            }

            // Validate Password (min 6 characters)
            if ($('#password').val().trim().length < 6) {
                valid = false;
                $('#message4').append('<div class="">Password must be at least 6 characters long.</div>');
            }

            // Validate Age
            if ($('#age').val().trim() === '' || $('#age').val() < 18) {
                valid = false;
                $('#message5').append('<div class="">Age must be 18 or older.</div>');
            }

            // Validate Gender
            if ($('#gender').val() === '') {
                valid = false;
                $('#message6').append('<div class="">Select the Gender .</div>');
            }

            // Validate Mobile Number (10 digits)
            const mobilePattern = /^\d{10}$/;
            if ($('#mobile').val().trim() === '' || !mobilePattern.test($('#mobile').val().trim())) {
                valid = false;
                $('#message8').append('<div class="">Enter a valid 10-digit mobile number.</div>');
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
