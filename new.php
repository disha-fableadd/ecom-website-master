<script>
    $(document).ready(function () {
    $("#place-order").click(function () {
        // Clear previous error messages
        $(".error-message").text("");

        let isValid = true;

        const formData = {
            user_id: <?php echo $user_id; ?>,  // Add user_id
            first_name: $("input[name='first_name']").val(),
            last_name: $("input[name='last_name']").val(),
            email: $("input[name='email']").val(),
            mobile: $("input[name='mobile']").val(),
            address: $("input[name='address']").val(),
            zip_code: $("input[name='zip_code']").val(),
            city: $("select[name='city']").val(),
            state: $("select[name='state']").val(),
            total: <?php echo $total; ?>
        };

        // Basic validation checks
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

                setTimeout(function() {
                    window.location.href = "success.php";
                }, 2000); // 2-second delay
            },
            error: function () {
                $("#modalMessage").text("Error placing order.");
                $("#orderModal").modal('show');
            }
        });
    });
});

</script>