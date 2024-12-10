
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
include 'db.php'; 


if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $results = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($results);
    $profile_picture = $user['profile_picture'];  
} else {
    $profile_picture = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>E-comm website</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <style>
            .form-control {
                display: block;
                width: 100%;
                height: calc(1.5em + 0.75rem + 2px);
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #D19C97;
                border-radius: 0;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

        .product-rating .stars i {
            color: #f39c12;
            font-size: 20px;
        }

        .review {
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .review h5 {
            font-size: 15px;
            font-weight: 600;
        }

        .review p {
            font-size: 14px;
            color: #555;
        }

        .social-icons a {
            display: inline-block;
            font-size: 15px;
            color: #fff;
            border-radius: 100%;
            text-align: center;
        }

        .social-icons .btn-facebook {
            background-color: #3b5998;
        }

        .social-icons .btn-twitter {
            background-color: #00acee;
        }

        .social-icons .btn-instagram {
            background-color: #e4405f;
        }

        .social-icons .btn-pinterest {
            background-color: #e60023;
        }

        .social-icons a:hover {
            opacity: 0.8;
            color: white;
        }

        h4 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        h5 {
            font-size: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container-fluid bg-light mb-5">
    <div class="row align-items-center py-3 px-xl-5">
        <!-- Logo -->
        <div class="col-lg-3 col-md-4">
            <a href="" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                </h1>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="col-lg-5">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="home.php" class="nav-item nav-link active">Home</a>
                        <a href="shop.php" class="nav-item nav-link">Shop</a>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="col-lg-4 col-md-3 text-right d-flex justify-content-end align-items-center">
        <a href="cart.php" class="btn border mx-1">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge count">0</span>
            </a>
            <?php if ($profile_picture): ?>
                <a href="profile.php" class="btn btn-primary mx-1">
                    <img src="uploads/<?php echo $profile_picture; ?>" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%;">
                </a>
                <a href="logout.php" class="btn btn-outline-primary mx-1">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary mx-1">Login</a>
                <a href="register.php" class="btn btn-outline-primary mx-1">Register</a>
            <?php endif; ?>

           
        </div>
    </div>
</div>

    <script>
        $(document).ready(function () {

            function updateProductCount() {
                $.ajax({
                    url: 'cart_product_count.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {

                        $('.count').text(data.total_products);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching product count: ", error);
                    }
                });
            }


            updateProductCount();

        });
    </script>