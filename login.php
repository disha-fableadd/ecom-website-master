

<?php
include 'header.php';
?>

<div class="container-fluid pt-5">
    <div class="row justify-content-center px-xl-5">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card border-0 shadow-lg p-4">
                <h3 class="text-center mb-4">Login</h3>
                <form id="loginForm" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter your email" >
                        <div id="message1" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" >
                        <div id="message2" class="text-danger"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
                <div id="message"></div>
            </div>
        </div>
    </div>
</div>



<?php
include 'footer.php';
?>
<script>
$(document).ready(function() {

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();  

        $('#message').html('');

        var email = $('#email').val().trim();
        var password = $('#password').val().trim();
        var isValid = true;

        // Client-side validation
        if (!email) {
            isValid = false;
            $('#message1').html('<div class="">Email is required .</div>');
        } else if (!validateEmail(email)) {
            isValid = false;
            $('#message1').html('<div class="">Invalid email format.</div>');
        } else if (!password) {
            isValid = false;
            $('#message2').html('<div class="">Password is required .</div>');
        } else if (password.length < 6) {
            isValid = false;
            $('#message2').html('<div class="">Password must be at least 6 characters long.</div>');
        }

        if (!isValid) return;

        var formData = $(this).serialize();

        $.ajax({
            url: 'login_page.php',  
            type: 'POST',  
            data: formData, 
            success: function(response) {
                if (response == 'success') {

                    window.location.href = 'home.php';
                } else {

                    $('#message').html('<div class="alert alert-danger">' + response + '</div>');
                }
            },
            error: function(xhr, status, error) {

                $('#message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
        });
    });

    // Email validation function
    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
</script>

