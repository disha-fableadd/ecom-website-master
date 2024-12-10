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
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
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
});
</script>
