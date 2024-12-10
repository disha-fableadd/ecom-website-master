<?php

include('db.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];


    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $profile_picture = null; 
        
    }


    $sql = "INSERT INTO users (first_name, last_name, email, password, age, gender, mobile, profile_picture)
            VALUES ('$first_name', '$last_name', '$email', '$password', '$age', '$gender', '$mobile', '$profile_picture')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
