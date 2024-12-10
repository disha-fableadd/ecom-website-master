<?php

include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $age = (int) $_POST['age']; 
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

   
    session_start();
    $user_id = $_SESSION['user_id']; 

    $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', mobile = '$mobile', age = $age, gender = '$gender' WHERE id = $user_id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(["success" =>"Updated successfully... "]);
    } else {
        echo json_encode(["error" => "Failed to update profile: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
