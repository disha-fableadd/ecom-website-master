<?php 

include 'header.php';
?>

 <style>
    .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    /* height: 100vh; */
    text-align: center;
}

.p404 {
    font-size: 100px;
    color: #ff4757;
    margin: 0;
}

.pragraph {
    font-size: 20px;
    margin: 10px 0 20px;
    color: #2f3542;
}

.buton {
    text-decoration: none;
    background-color: #1e90ff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
}

.buton:hover {
    background-color: #3742fa;
}

 </style>

    <div class="container text-center py-5">
        <h1 class="p404">404</h1>
        <p class="pragraph">Oops! The page you are looking for cannot be found.</p>
        <a href="Home.php" class="btn btn-primary buton">Go to Home Page</a>
    </div>

<?php 

include 'footer.php';
?>
